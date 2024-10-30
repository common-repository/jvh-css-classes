jQuery(function($) {
	class ClassEditor {
		constructor() {
			this.addTriggers();
		}

		addTriggers() {
			$(document).on('click', '.edit_link_wrapper a', (event) => {
				event.preventDefault();
				this.wrapper = $(event.target).closest('.edit_link_wrapper');
				this.postid = this.wrapper.attr('data-postid');

				this.toggleForm();

				// Refresh the editor after making it visible
				if (window.codeMirrorInstances[this.postid]) {
					window.codeMirrorInstances[this.postid].refresh();
				}
			});

			// Get class styles on mouseover. Workaround because on click popup is already closed and removed.
			$(document).on('mouseenter', '.vc_ui-panel-footer span[data-vc-ui-element=button-save]', () => { this.setupData(); });

			$(document).on('click', '.vc_ui-panel-footer span[data-vc-ui-element=button-save]', () => { this.save(); });

			$(document).on('click', '.save_css', () => {
				this.setupData();
				this.save();
			});

			$(document).on('keydown', (event) => {
				if (event.ctrlKey && event.shiftKey && event.key === 'S') {
					this.setupData();
					this.save();
				}
			});
		}

		toggleForm() {
			if (this.isFormVisible()) {
				$('.edit_class_form', this.wrapper).hide();
			}
			else {
				$('.edit_class_form', this.wrapper).show();
			}
		}

		isFormVisible() {
			return $('.edit_class_form', this.wrapper).is(':visible');
		}

		save() {
			if (this.data.length === 0) {
				return;
			}

			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				type: 'post',
				data: {
					action: 'save_css',
					classes: this.data,
				},
				success: (response) => {
					if ($('#gvcpreview').length) {
						$('#gvcpreview')[0].contentWindow.location.reload();
					}

					const $saveCssButton = $('.save_css');
					const originalText = $saveCssButton.text();

					$saveCssButton.css('background-color', 'green');
					$saveCssButton.text('SAVED!');

					setTimeout(() => {
						$saveCssButton.css('background-color', '');
						$saveCssButton.text(originalText);
					}, 2000);
				}
			});
		}

		setupData() {
			var data = [];

			$('.edit_class_form:visible textarea.class_css').each(function() {
				var cssClass = {};

				const postid = $(this).attr('id').replace('class_', '');
				const styles = $(this).val();
				const categoryIds = $(`#categories_${postid}`).val();

				cssClass.postid = postid;
				cssClass.styles = styles;
				cssClass.categoryIds = categoryIds;

				data.push(cssClass);
			});

			this.data = data;
		}
	}

	const classEditor = new ClassEditor();
});

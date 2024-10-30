jQuery(function($) {
	class ClassSaver {
		constructor() {
			this.addTriggers();
		}

		addTriggers() {
			$(document).on('click', '.toggle_new_class', () => { this.toggleForm(); })
			$(document).on('change', '.new_class_title', () => { this.maybeSetClassName(); })
			$(document).on('click', '.save_class', () => { this.maybeSave(); })
		}

		toggleForm() {
			if (this.isFormVisible()) {
				$('.new_css_class_form', this.wrapper).hide();
			}
			else {
				$('.new_css_class_form', this.wrapper).show();

				if (!$('.new_css_class_form', this.wrapper).hasClass('codemirror_active')) {
					this.enableCodeMirror();
					$('.new_css_class_form', this.wrapper).addClass('codemirror_active');
				}
			}
		}

		maybeSave() {
			if (this.shouldSave()) {
				this.save();
			}
			else {
				alert('Please fill in all fields above before saving.');
			}
		}

		shouldSave() {
			return this.isTitleSet() && this.isNameSet() && this.isStylesSet();
		}

		save() {
			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				type: 'post',
				data: {
					action: 'save_new',
					title: this.title,
					name: this.name,
					categories: this.categories,
					styles: this.styles
				},
				beforeSend: () => {
					this.enableLoader();
				},
				success: (newClassId) => {
					this.newClassId = newClassId;
					this.addClass();
					this.clearFields();
					this.toggleForm();
					this.disableLoader();
				},
			});
		}

		enableLoader() {
			$('.save_class').addClass('loading');
		}

		disableLoader() {
			$('.save_class').removeClass('loading');
		}

		addClass() {
			$('.extra_css_class').append(`<option value="${this.name}">${this.title} (#${this.newClassId})</option>`);

			var classes = this.selectedClasses;

			classes.push(this.name);

			jQuery('.extra_css_class').val(classes).trigger('change');
		}

		clearFields() {
			$('.new_class_name, .new_class_title, #new_class_textarea, .new_class_category').val('').trigger('change');
			this.editor.setValue( "\n".repeat(4) )
		}

		maybeSetClassName() {
			if (this.shouldSetClassName()) {
				this.setClassName();
			}
		}

		setClassName() {
			$('.new_class_name').val(this.autoName);
		}

		shouldSetClassName() {
			return this.isTitleSet() && !this.isNameSet();
		}

		isFormVisible() {
			return $('.new_css_class_form', this.wrapper).is(':visible');
		}

		isTitleSet() {
			return this.title.length > 0;
		}

		isNameSet() {
			return this.name.length > 0;
		}

		isStylesSet() {
			return this.styles.length > 0;
		}

		enableCodeMirror(postid) {
			if ( typeof CodeMirror !== 'undefined' ) {
				this.editor = CodeMirror.fromTextArea(document.getElementById('new_class_textarea'), {
					mode: "css",
					lineNumbers: true,
					theme: 'dracula',
				});

				this.editor.save();
				this.editor.refresh();

				this.editor.setValue( "\n".repeat(4) )

				this.editor.on('change', () => {
					this.editor.save();
				});
			}
		}

		get title() {
			return $('.new_class_title').val();
		}

		get name() {
			return $('.new_class_name').val();
		}

		get categories() {
			return $('.new_class_category').val();
		}

		get autoName() {
			return wpFeSanitizeTitle(this.title);
		}

		get styles() {
			return $('#new_class_textarea').val();
		}

		get selectedClasses() {
			return $('.extra_css_class').val();
		}
	}

	const classSaver = new ClassSaver();
});

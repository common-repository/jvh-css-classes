<?php

namespace JVH\CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin
{
	public function setup() {
		$this->register_post_type();
		$this->hide_meta_boxes();
		$this->add_acf_fields();
		$this->apply_extra_css_classes();
		$this->add_dropdown_multi_field();
		$this->add_class_attribute_vc_elements();
		$this->setup_classes_overview();
		$this->add_assets();
		$this->add_ajax_hooks();
	}

	private function register_post_type() {
		add_action( 'init', function() {
			register_post_type('css-class', [
				'public'    => true,
				'label'     => 'CSS class',
				'supports'  => [
					'title',
					'thumbnail',
				],
				'exclude_from_search' => true,
				'labels' => [
					'name'                  => 'CSS classes',
					'singular_name'         => 'CSS class',
					'menu_name'             => 'CSS classes',
					'name_admin_bar'        => 'CSS class',
					'add_new'               => 'Add CSS class',
					'add_new_item'          => 'Add new CSS class',
					'new_item'              => 'New CSS class',
					'edit_item'             => 'Edit CSS class',
					'view_item'             => 'View CSS class',
					'all_items'             => 'All CSS classes',
				],
			]);

			add_post_type_support( 'css-class', 'revisions' );
		} );
	}

	private function hide_meta_boxes() {
		add_action('add_meta_boxes', function() {
			remove_meta_box('wpseo_meta', 'css-class', 'normal');
			remove_meta_box('cptemplates-template', 'css-class', 'normal');
			remove_meta_box('formatdiv', 'css-class', 'side');
		}, 100);
	}

	private function add_acf_fields() {
		add_action( 'init', function() {
			if( function_exists('acf_add_local_field_group') ) {
				acf_add_local_field_group(array(
					'key' => 'group_61c0654dde3fb',
					'title' => 'CSS Class fields',
					'fields' => array(
						array(
							'key' => 'field_61c065a9d0e72',
							'label' => 'CSS class',
							'name' => 'css_class',
							'type' => 'text',
							'instructions' => 'This class will be applied to the VC element (wrapper)',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'dsv_formatting' => array(
								'format' => 'display',
								'disable' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_61c065702d0a8',
							'label' => 'CSS styles',
							'name' => 'css_styles',
							'type' => 'textarea',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'dsv_formatting' => array(
								'format' => 'display',
								'disable' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'mode' => 'css',
							'lines' => 1,
							'indent_unit' => 4,
							'maxlength' => '',
							'rows' => 4,
							'max_rows' => '',
							'return_entities' => 0,
						),
					),
					'location' => array(
						array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'css-class',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => true,
					'description' => '',
					'acfe_display_title' => '',
					'acfe_autosync' => '',
					'acfe_form' => 0,
					'acfe_meta' => '',
					'acfe_note' => '',
				));

			}
		} );
	}

	private function apply_extra_css_classes() {
		add_filter( 'do_shortcode_tag', function( $output, $tag, $attr, $match ) {
			if ( ! isset( $attr['extra_css_class'] ) || empty( $attr['extra_css_class'] ) ) {
				return $output;
			}

			/*
			 * Add extra CSS class by replacing the first occurance of class=" with class="{extra_class}
			 * Not all VC elements have an option for extra classes to be added
			 * Some use different params and some don't have any vc param
			 * So this method adds the class to any element with at least one HTML class attribute
			 */
			$pos = strpos( $output, 'class="' );

			if ( $pos !== false ) {
				$css_classes = str_replace( ',', ' ', $attr['extra_css_class'] );
				$replace = 'class="' . $css_classes . ' ';

				$output = substr_replace( $output, $replace, $pos, strlen( 'class="' ) );
				
				$classes_array = explode( ',', $attr['extra_css_class'] );
						
				$css_classes = new CssClasses();
				$styles = $css_classes->getStylesForClasses( $classes_array );
				
				$output .= $styles;
			}

			return $output;
		}, 10, 4 );
	}

	private function add_dropdown_multi_field() {
		vc_add_shortcode_param( 'dropdown_multi', function( $param, $value ) {
		   $param_line = '';
		   $param_line .= '<select multiple name="'. esc_attr( $param['param_name'] ).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param['param_name'] ).' '. esc_attr($param['type']).'">';
		   foreach ( $param['value'] as $text_val => $val ) {
			   if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
							$text_val = $val;
						}
						$text_val = __($text_val, "js_composer");
						$selected = '';

						if(!is_array($value)) {
							$param_value_arr = explode(',',$value);
						} else {
							$param_value_arr = $value;
						}

						if ($value!=='' && in_array($val, $param_value_arr)) {
							$selected = ' selected="selected"';
						}
						$param_line .= '<option class="'.$val.'" value="'.$val.'"'.$selected.'>'.$text_val.'</option>';
					}
		   $param_line .= '</select>';

		   return  $param_line;
		} );
	}

	private function add_class_attribute_vc_elements() {
		add_action( 'admin_init', function() {
			$all_shortcodes = \WPBMap::getShortCodes();

			$css_classes = new CssClasses();
			$choices = $css_classes->getVcDropdownChoices();
			  
			$attributes = [
			  	[
			  		'type' => 'dropdown_multi',
			  		'heading' => 'Extra CSS class',
			  		'param_name' => "extra_css_class",
			  		'group' => 'Extra CSS class',
			  		'value' => $choices,
			  		'admin_label' => true,
			  	],
			];

			// Add empty params attribute to row and column, so classes option will work on rows and columns

			if ( ! isset( $all_shortcodes['vc_row']['params'] ) ) {
				$all_shortcodes['vc_row']['params'] = [];
			}

			if ( ! isset( $all_shortcodes['vc_row_inner']['params'] ) ) {
				$all_shortcodes['vc_row_inner']['params'] = [];
			}

			if ( ! isset( $all_shortcodes['vc_column']['params'] ) ) {
				$all_shortcodes['vc_column']['params'] = [];
			}

			if ( ! isset( $all_shortcodes['vc_column_inner']['params'] ) ) {
				$all_shortcodes['vc_column_inner']['params'] = [];
			}

			if ( ! isset( $all_shortcodes['vc_section']['params'] ) ) {
				$all_shortcodes['vc_section']['params'] = [];
			}

			foreach ( $all_shortcodes as $name => $settings ) {
				if ( strpos( $name, 'dt_' ) !== false && ! isset( $settings['params'] ) ) {
					$settings['params'] = [];
				}

			  if ( ! isset( $settings['params'] ) ) {
				  continue;
			  }

			  vc_add_params( $name, $attributes );
			}

			vc_add_params( 'vc_column_text', $attributes );
		}, 51 );
	}

	private function setup_classes_overview() {
		$this->add_featured_image_overview();
		$this->remove_unnecessary_columns();
		$this->add_css_categories();
	}

	private function add_featured_image_overview() {
		$this->add_featured_image_column();
		$this->show_featured_image_overview();
	}

	private function add_featured_image_column() {
		add_filter( 'manage_css-class_posts_columns', function( $columns ) {
			$columns['featured_image'] = 'Preview';

			return $columns;
		} );
	}

	private function show_featured_image_overview() {
		add_action( 'manage_css-class_posts_custom_column', function( $column, $post_id ) {
			switch ( $column ) {
				case 'featured_image' :
					the_post_thumbnail( 'full', $post_id );
					break;
			}
		}, 10, 2 );
	}

	private function remove_unnecessary_columns() {
		add_filter( 'manage_css-class_posts_columns', function( $columns ) {
			unset( $columns['date'] );
			unset( $columns['cptemplate'] );
			
			return $columns;
		}, 11 );
	}

	private function add_css_categories() {
		$this->add_css_category_taxonomy();
		$this->add_css_category_filter();
	}

	private function add_css_category_taxonomy() {
		add_action( 'init', function() {
			register_taxonomy( 'css_class_categorie', 'css-class', [
				'hierarchical'      => true,
				'public'            => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => false,
				'show_in_rest'      => false,
				'labels'            => [
					'name'              => 'Categories',
					'singular_name'     => 'Category',
					'view_item'         => 'View category',
					'edit_item'         => 'Edit category',
					'update_item'       => 'Update category',
					'add_new_item'      => 'Add new category',
					'new_item_name'     => 'New category',
					'menu_name'         => 'Category',
				],
			] );
		} );
	}

	private function add_css_category_filter() {
		add_action( 'restrict_manage_posts', function( $post_type, $which ) {
			if ( 'css-class' !== $post_type ) {
				return;
			}

			$terms = get_terms( 'css_class_categorie' );

			echo '<input type="hidden" name="taxonomy" value="css_class_categorie" />';

			echo '<select name="term" class="postform">';
				echo '<option value="">All categories</option>';

				foreach ( $terms as $term ) { 
					$selected = '';

					if ( isset( $_GET['term'] ) && $_GET['term'] === $term->slug ) {
						$selected = 'selected="selected"';
					}

					echo '<option value="' . esc_attr( $term->slug ) . '" ' . esc_html( $selected ) . '>' . esc_html( $term->name ) . '</option>';
				}
			echo '</select>';
		}, 10, 2);
	}

	private function add_assets() {
		add_action( 'admin_enqueue_scripts', function() {
			if ( isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) {
				wp_enqueue_script( 'codemirror', JVHCSS_PLUGIN_URL . 'assets/js/codemirror.min.js', array(), '5.65.0', true );
				wp_enqueue_script( 'codemirror-css', JVHCSS_PLUGIN_URL . 'assets/js/css.min.js', array(), '5.65.0', true );
				wp_enqueue_style( 'codemirror', JVHCSS_PLUGIN_URL . "assets/css/codemirror.min.css", array(), '5.65.0' );
				wp_enqueue_style( 'codemirror-dracula', JVHCSS_PLUGIN_URL . "assets/css/dracula.min.css", array(), '5.65.0' );
			}

			wp_enqueue_style( 'css-classes', JVHCSS_PLUGIN_URL . "assets/css/css-classes.css", array(), filemtime( JVHCSS_PLUGIN_PATH . 'assets/css/css-classes.css' ) );
			wp_enqueue_script( 'css-classes', JVHCSS_PLUGIN_URL . 'assets/js/css-classes.js', array(), filemtime( JVHCSS_PLUGIN_PATH . 'assets/js/css-classes.js' ), true );
			wp_enqueue_script( 'edit-class-popup', JVHCSS_PLUGIN_URL . 'assets/js/edit-class-popup.js', array(), filemtime( JVHCSS_PLUGIN_PATH . 'assets/js/edit-class-popup.js' ), true );
			wp_enqueue_script( 'save-class-popup', JVHCSS_PLUGIN_URL . 'assets/js/save-class-popup.js', array(), filemtime( JVHCSS_PLUGIN_PATH . 'assets/js/save-class-popup.js' ), true );
		} );
	}

	private function wp_admin_post_type () {
		global $post, $parent_file, $typenow, $current_screen, $pagenow;

		$post_type = NULL;

		if($post && (property_exists($post, 'post_type') || method_exists($post, 'post_type')))
			$post_type = $post->post_type;

		if(empty($post_type) && !empty($current_screen) && (property_exists($current_screen, 'post_type') || method_exists($current_screen, 'post_type')) && !empty($current_screen->post_type))
			$post_type = $current_screen->post_type;

		if(empty($post_type) && !empty($typenow))
			$post_type = $typenow;

		if(empty($post_type) && function_exists('get_current_screen'))
			$post_type = get_current_screen();

		if(empty($post_type) && isset($_REQUEST['post']) && !empty($_REQUEST['post']) && function_exists('get_post_type') && $get_post_type = get_post_type((int)$_REQUEST['post']))
			$post_type = $get_post_type;

		if(empty($post_type) && isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']))
			$post_type = sanitize_key($_REQUEST['post_type']);

		if(empty($post_type) && 'edit.php' == $pagenow)
			$post_type = 'post';

		return $post_type;
	}

	private function add_ajax_hooks() {
		$this->add_ajax_get_classes_hook();
		$this->add_ajax_save_hook();
		$this->add_ajax_save_new_hook();
		$this->add_populate_classes_hook();
	}

	private function add_ajax_get_classes_hook() {
		add_action( 'wp_ajax_get_classes_css', function() {
			$css = array();

			$classes = $_POST['classes'];

			if ( ! is_array( $classes ) ) {
				return $css;
			}

			foreach ( $classes as $class ) {
				$post_id = $class['id'];

				if ( ! is_numeric( $post_id ) ) {
					continue;
				}

				$css_class = new CssClass( $post_id );

				$css[] = array(
					'post_id' => $post_id,
					'styles' => $css_class->getStyles(),
					'category_ids' => $css_class->getCategoryIds(),
				);
			}

			echo json_encode( $css );

			wp_die();
		} );
	}

	private function add_populate_classes_hook() {
		add_action( 'wp_ajax_populate_class_categories', function() {
			foreach ( $this->get_categories() as $category ) {
				echo "<option value=\"{$category->term_id}\">{$category->name}</option>";
			}

			wp_die();
		} );
	}

	private function get_categories() {
		return get_terms(
			array(
				'taxonomy' => 'css_class_categorie',
				'hide_empty' => false,
			)
		);
	}

	private function add_ajax_save_hook() {
		add_action( 'wp_ajax_save_css', function() {
			$classes = $_POST['classes'];

			if ( ! is_array( $classes ) ) {
				return;
			}

			foreach ( $classes as $class ) {
				$post_id = $class['postid'];

				if ( ! is_numeric( $post_id ) ) {
					continue;
				}

				$styles = $class['styles'];

				update_post_meta( $post_id, 'css_styles', $styles );

				if ( isset( $class['categoryIds'] ) && is_array( $class['categoryIds'] ) ) {
					$term_ids = array_map( 'intval', $class['categoryIds'] );

					wp_set_object_terms( $post_id, $term_ids, 'css_class_categorie' );
				}
			}

			wp_die();
		} );
	}

	private function add_ajax_save_new_hook() {
		add_action( 'wp_ajax_save_new', function() {
			$title = sanitize_title( $_POST['title'] );
			$name = sanitize_title( $_POST['name'] );
			$styles = sanitize_text_field( $_POST['styles'] );

			$post_id = wp_insert_post( array(
				'post_title' => $title,
				'post_type' => 'css-class',
				'post_status' => 'publish',
				'meta_input' => array(
					'css_class' => $name,
					'css_styles' => $styles,
				),
			) );

			if ( isset( $_POST['categories'] ) ) {
				$term_ids = array_map( 'intval', $_POST['categories'] );

				wp_set_object_terms( $post_id, $term_ids, 'css_class_categorie' );
			}

			echo $post_id;

			wp_die();
		} );
	}
}

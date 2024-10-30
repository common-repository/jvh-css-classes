<?php

namespace JVH\CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CssClass
{
	public $post_id;

	public function __construct( $post_id )
	{
		$this->post_id = $post_id;
	}
	
	public static function getIdByClass( $css_class )
	{
		$the_query = new \WP_Query([
			'posts_per_page' => 1,
			'post_type' => 'css-class',
			'meta_key' => 'css_class',
			'meta_value' => $css_class,
		]);

		return $the_query->posts[0]->ID;
	}

	public function getTitle()
	{
		return get_the_title( $this->post_id );
	}

	public function getClassName()
	{
		return get_post_meta( $this->post_id, 'css_class', true );
	}

	public function getStyles()
	{
		return get_post_meta( $this->post_id, 'css_styles', true );
	}

	public function getCategoryIds() {
		$terms = wp_get_post_terms( $this->post_id, 'css_class_categorie' );
		$term_ids = wp_list_pluck($terms, 'term_id');

		return $term_ids;
	}
}

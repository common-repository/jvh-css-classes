<?php

namespace JVH\CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CssClasses
{
	public function getStylesForClasses( $classes )
	{
		$styles = '<style>';
		
		foreach ( $classes as $class ) {
			$post_id = CssClass::getIdByClass( $class );
			$css_class = new CssClass( $post_id );
			
			$styles .= $css_class->getStyles();
		}
		
		$styles .= '</style>';
		
		return $styles;
	}
	
	public function getVcDropdownChoices()
	{
		return array_merge( $this->getDropdownPlaceholder(), $this->getCptDropwdownChoices(), $this->getThemeDropdownChoices() );
	}
	
	private function getDropdownPlaceholder()
	{
		return ['' => ''];
	}

	private function getCptDropwdownChoices()
	{
		$choices = [];

		foreach ( $this->getClasses() as $css_class ) {
			$choices[$css_class->getTitle() . " (#{$css_class->post_id})"] = $css_class->getClassName();
		}

		return $choices;
	}

	private function getThemeDropdownChoices()
	{
		return [
			'Theme: bg-gradient-primary (background)' => 'bg-gradient-primary',
			'Theme: bg-primary (background)' => 'bg-primary',
			'Theme: bg-secondary (background)' => 'bg-secondary',
			'Theme: bg-green (background)' => 'bg-green',
			'Theme: bg-blue (background)' => 'bg-blue',
			'Theme: bg-yellow (background)' => 'bg-yellow',
			'Theme: bg-white (background)' => 'bg-white',
			'Theme: bg-black (background)' => 'bg-black',
			'Theme: bg-brown (background)' => 'bg-brown',
			'Theme: bg-cyan (background)' => 'bg-cyan',
			'Theme: bg-orange (background)' => 'bg-orange',
			'Theme: bg-red (background)' => 'bg-red',
			'Theme: bg-pruple (background)' => 'bg-pruple',

			'Theme: bg-green-light (background)' => 'bg-green-light',
			'Theme: bg-blue-light (background)' => 'bg-blue-light',
			'Theme: bg-yellow-light (background)' => 'bg-yellow-light',
			'Theme: bg-brown-light (background)' => 'bg-brown-light',
			'Theme: bg-cyan-light (background)' => 'bg-cyan-light',
			'Theme: bg-orange-light (background)' => 'bg-orange-light',
			'Theme: bg-red-light (background)' => 'bg-red-light',
			'Theme: bg-pruple-light (background)' => 'bg-pruple-light',

			'Theme: bg-gray-1 (background)' => 'bg-gray-1',
			'Theme: bg-gray-2 (background)' => 'bg-gray-2',
			'Theme: bg-gray-3 (background)' => 'bg-gray-3',
			'Theme: bg-gray-4 (background)' => 'bg-gray-4',
			'Theme: bg-gray-5 (background)' => 'bg-gray-5',
			'Theme: bg-gray-6 (background)' => 'bg-gray-6',
			'Theme: bg-gray-7 (background)' => 'bg-gray-7',
			'Theme: bg-gray-8 (background)' => 'bg-gray-8',
			'Theme: bg-gray-9 (background)' => 'bg-gray-9',

			'Theme: bg-dark-opacity-1 (background)' => 'bg-dark-opacity-1',
			'Theme: bg-dark-opacity-2 (background)' => 'bg-dark-opacity-2',
			'Theme: bg-dark-opacity-3 (background)' => 'bg-dark-opacity-3',
			'Theme: bg-dark-opacity-4 (background)' => 'bg-dark-opacity-4',
			'Theme: bg-dark-opacity-5 (background)' => 'bg-dark-opacity-5',
			'Theme: bg-dark-opacity-6 (background)' => 'bg-dark-opacity-6',
			'Theme: bg-dark-opacity-7 (background)' => 'bg-dark-opacity-7',
			'Theme: bg-dark-opacity-8 (background)' => 'bg-dark-opacity-8',
			'Theme: bg-dark-opacity-9 (background)' => 'bg-dark-opacity-9',

			'Theme: bg-light-opacity-1 (background)' => 'bg-light-opacity-1',
			'Theme: bg-light-opacity-2 (background)' => 'bg-light-opacity-2',
			'Theme: bg-light-opacity-3 (background)' => 'bg-light-opacity-3',
			'Theme: bg-light-opacity-4 (background)' => 'bg-light-opacity-4',
			'Theme: bg-light-opacity-5 (background)' => 'bg-light-opacity-5',
			'Theme: bg-light-opacity-6 (background)' => 'bg-light-opacity-6',
			'Theme: bg-light-opacity-7 (background)' => 'bg-light-opacity-7',
			'Theme: bg-light-opacity-8 (background)' => 'bg-light-opacity-8',
			'Theme: bg-light-opacity-9 (background)' => 'bg-light-opacity-9',

			'Theme: text-body-default (text color)' => 'text-body-default',
			'Theme: text-heading-default (text color)' => 'text-heading-default',
			'Theme: text-primary (text color)' => 'text-primary',
			'Theme: text-secondary (text color)' => 'text-secondary',

			'Theme: text-white (text color)' => 'text-white',
			'Theme: text-blue (text color)' => 'text-blue',
			'Theme: text-green (text color)' => 'text-green',
			'Theme: text-cyan (text color)' => 'text-cyan',
			'Theme: text-yellow (text color)' => 'text-yellow',
			'Theme: text-orange (text color)' => 'text-orange',
			'Theme: text-red (text color)' => 'text-red',
			'Theme: text-brown (text color)' => 'text-brown',
			'Theme: text-purple (text color)' => 'text-purple',

			'Theme: fly-sm (animation)' => 'fly-sm',
			'Theme: fly (animation)' => 'fly',
			'Theme: fly-lg (animation)' => 'fly-lg',

			'Theme: scale-sm (animation)' => 'scale-sm',
			'Theme: scale (animation)' => 'scale',
			'Theme: scale-lg (animation)' => 'scale-lg',

			'Theme: scale-inverse-sm (animation)' => 'scale-inverse-sm',
			'Theme: scale-inverse (animation)' => 'scale-inverse',
			'Theme: scale-inverse-lg (animation)' => 'scale-inverse-lg',

			'Theme: shadow-sm' => 'shadow-sm',
			'Theme: shadow' => 'shadow',
			'Theme: shadow-lg' => 'shadow-lg',

			'Theme: shadow-hover-sm' => 'shadow-hover-sm',
			'Theme: shadow-hover' => 'shadow-hover',
			'Theme: shadow-hover-lg' => 'shadow-hover-lg',
			'Theme: shadow-inverse-hover-sm' => 'shadow-inverse-hover-sm',

			'Theme: shadow-inverse-hover-sm' => 'shadow-inverse-hover-sm',
			'Theme: shadow-inverse-hover' => 'shadow-inverse-hover',
			'Theme: shadow-inverse-hover-lg' => 'shadow-inverse-hover-lg',

			'Theme: shadow-0 (remove shadow)' => 'shadow-0',
		];
	}

	public function getAllStyles()
	{
		if ( ! $this->hasCssClasses() ) {
			return;
		}

		$styles = '<style>';

		foreach ( $this->getClasses() as $css_class ) {
			$styles .= $css_class->getStyles();
		}

		$styles .= '</style>';

		return $styles;
	}

	private function hasCssClasses()
	{
		return count( $this->getClasses() ) > 0;
	}

	private function getClasses()
	{
		$classes = [];

		foreach ( $this->getPosts() as $post ) {
			$classes[] = new CssClass( $post->ID );
		}

		return $classes;
	}

	private function getPosts()
	{
		$the_query = new \WP_Query([
			'posts_per_page' => -1,
			'post_type' => 'css-class',
		]);

		return $the_query->posts;
	}
}

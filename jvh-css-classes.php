<?php
/**
 * Plugin Name:       JVH CSS Classes
 * Description:       CSS classes CPT for VC elements
 * Version:           1.1.6
 * Author:            JVH webbouw
 * Author URI:        https://jvhwebbouw.nl
 * License:           GPL-v3
 * Requires PHP:      7.3
 * Requires at least: 5.0
 */

namespace JVH\CSS;

define( 'JVHCSS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'JVHCSS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

foreach ( glob( __DIR__ . '/inc/*.php' ) as $file ) {
    require_once $file;
}

$plugin = new Plugin();
$plugin->setup();

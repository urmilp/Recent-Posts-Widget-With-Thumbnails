<?php
/*
Plugin Name: MT Recent Posts Widget With Thumbnails
Plugin URI:  http://metataggsolutions.com/
Description: Small and fast plugin to display in the sidebar a list of linked titles and thumbnails of the most recent postings
Version:     1.4.4
Author:      Meta tagg
Author URI:  http://metataggsolutions.com/
Text Domain: recent-posts-widget-with-thumbnails
Domain Path: /languages
*/ 
 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Basic plugin definitions
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.0.0
 */
if( !defined( 'WPRPWT_VERSION' ) ) {
    define( 'WPRPWT_VERSION', '1.4.4' ); // Version of plugin
}
if( !defined( 'WPRPWT_DIR' ) ) {
    define( 'WPRPWT_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPRPWT_URL' ) ) {
    define( 'WPRPWT_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPRPWT_POST_TYPE' ) ) {
    define( 'WPRPWT_POST_TYPE', 'post' ); // Plugin post type
}

add_action('plugins_loaded', 'mtrpwt_load_textdomain');
function mtrpwt_load_textdomain() {
    load_plugin_textdomain( 'mt-recent-posts-widget-with-thumbnails', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package MT Recent Posts Widget With Thumbnails
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'mtrpwt_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package MT Recent Posts Widget With Thumbnails
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'mtrpwt_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * @package 
 * @since 1.0.0
 */
function mtrpwt_install() {

// IMP need to flush rules for custom registered post type
flush_rewrite_rules();	
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @package 
 * @since 1.0.0
 */
function mtrpwt_uninstall() {
    
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

// Action to add style at front side
add_action( 'wp_enqueue_scripts', 'mtrpwt_front_style' );
/**
 * Function to add style at front side
 * 
 * @package MT Recent Posts Widget With Thumbnails
 * @since 1.0.0
 */
function mtrpwt_front_style() {		
	
	// Registring and enqueing public css
	wp_register_style( 'mtrpwt-public-style', WPRPWT_URL.'assets/css/mtrpwt-public.css', array(), WPRPWT_VERSION );
	wp_enqueue_style( 'mtrpwt-public-style' );
}

// Function file
require_once( WPRPWT_DIR . '/includes/mtrpwt-function.php' );
		
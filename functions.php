<?php
/**
 * Wp Theme Boilerplate functions and definitions.
 * php version 7.2.10
 *
 * @category Functions
 * @package  WpThemeBoilerplate
 * @author   IDEAMOS TU WEB <webmaster@ideamostuweb.com>
 * @license  GNU General Public License v2 or later
 * @link     https://developer.wordpress.org/themes/basics/theme-functions/
 */

add_action( 'after_setup_theme', 'wp_theme_boilerplate_setup' );

if ( ! function_exists( 'wp_theme_boilerplate_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @return mixed
	 */
	function wp_theme_boilerplate_setup() {
		/*
		 * Theme Settings
		 */
		include trailingslashit( get_template_directory() ) . 'inc/theme_settings.php';

		/*
		 * TGM
		 */
		include trailingslashit( get_template_directory() ) . 'tgmpa/tgm_init.php';

		/*
		 * Theme Options
		 */
		include trailingslashit( get_template_directory() ) . 'inc/options_init.php';
	}
endif;

// Removes some links from the header.
add_action( 'init', 'wp_theme_boilerplate_remove_headlinks' );
if ( ! function_exists( 'wp_theme_boilerplate_remove_headlinks' ) ) :

	/**
	 * Documented wp_theme_boilerplate_remove_headlinks function
	 *
	 * @return void
	 */
	function wp_theme_boilerplate_remove_headlinks() {
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}
endif;


add_action( 'wp_enqueue_scripts', 'wp_theme_boilerplate_css_js' );
if ( ! function_exists( 'wp_theme_boilerplate_css_js' ) ) :

	/**
	 * Documented wp_theme_boilerplate_css_js function
	 *
	 * @return void
	 */
	function wp_theme_boilerplate_css_js() {
		$theme_version  = wp_get_theme()->get( 'Version' );
		$version_string = is_string( $theme_version ) ? $theme_version : false;

		wp_enqueue_style( 'wp_theme_boilerplate-styles', get_template_directory_uri() . '/dist/css/app.css', array(), $version_string );
		wp_enqueue_script(
			'wp_theme_boilerplate-bootstrap_js',
			get_template_directory_uri() . '/dist/js/bootstrap.js',
			array( 'jquery' ),
			'4,6',
			true
		);
		wp_enqueue_script(
			'wp_theme_boilerplate-js',
			get_template_directory_uri() . '/dist/js/app.js',
			array( 'jquery' ),
			$version_string,
			true
		);
	}
endif;

add_filter( 'wp_handle_upload_prefilter', 'sanitize_file_uploads', 1 );
/**
 * Function Sanitize File Uploads
 *
 * @param mixed $file
 * @return boolean
 */
function sanitize_file_uploads( $file ) {
	$abc = 'abc';
	if ( ! isset( $_REQUEST['post_id'] ) ) {
		return $file;
	}
	$file['name'] = sanitize_file_name( $file['name'] );
	$file['name'] = strtolower( $file['name'] );
	$file['name'] = $abc . '-' . $file['name'];

	return $file;
}


add_action( 'send_headers', 'wp_theme_boilerplate_block_iframes' );
if ( ! function_exists( 'wp_theme_boilerplate_block_iframes' ) ) {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	function wp_theme_boilerplate_block_iframes() {
		header( 'X-FRAME-OPTIONS: SAMEORIGIN' );
	}
}

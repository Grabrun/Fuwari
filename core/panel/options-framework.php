<?php
/**
 * Options Framework
 *
 * @package   Options Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 *
 * @wordpress-plugin
 * Plugin Name: Options Framework
 * Plugin URI:  http://wptheming.com
 * Description: A framework for building theme options.
 * Version:     1.9.0
 * Author:      Devin Price
 * Author URI:  http://wptheming.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: optionsframework
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Don't load if optionsframework_init is already defined
if (is_admin() && ! function_exists( 'optionsframework_init' ) ) :

function optionsframework_init() {

	//  If user can't edit theme options, exit
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// Loads the required Options Framework classes.
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-interface.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-sanitization.php';

	// Instantiate the options page.
	$options_framework_admin = new Options_Framework_Admin;
	$options_framework_admin->init();

	// Instantiate the media uploader class
	$options_framework_media_uploader = new Options_Framework_Media_Uploader;
	$options_framework_media_uploader->init();

}

add_action( 'init', 'optionsframework_init', 20 );

endif;


/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
if ( ! function_exists( 'get_fuwari' ) ) :
function get_fuwari( $name, $default = false ) {

	// 静态缓存：单次请求内选项只读一次数据库（架构优化）
	static $fuwari_options_cache = null;

	if ( null === $fuwari_options_cache ) {
		$option_name = '';

		// Gets option name as defined in the theme
		if ( function_exists( 'optionsframework_option_name' ) ) {
			$option_name = optionsframework_option_name();
		}

		// Fallback option name
		if ( '' == $option_name ) {
			$option_name = get_option( 'stylesheet' );
			$option_name = preg_replace( "/\W/", "_", strtolower( $option_name ) );
		}

		// Get option settings from database
		$fuwari_options_cache = get_option( $option_name );
		if ( ! is_array( $fuwari_options_cache ) ) {
			$fuwari_options_cache = array();
		}
	}

	// Return specific option
	if ( isset( $fuwari_options_cache[$name] ) ) {
		return $fuwari_options_cache[$name];
	}

	// 0.3.0 起选项 id 由 boxmoe_* 更名为 fuwari_*：读取时回退旧键，避免升级后设置丢失
	if ( 0 === strpos( $name, 'fuwari_' ) ) {
		$legacy_key = 'boxmoe_' . substr( $name, 7 );
		if ( isset( $fuwari_options_cache[$legacy_key] ) ) {
			return $fuwari_options_cache[$legacy_key];
		}
	}

	return $default;
}
endif;
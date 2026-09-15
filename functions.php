<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 */

//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}
//时区设置
date_default_timezone_set('Asia/Shanghai');

//boxmoe.com===加载面板
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/core/panel/' );
require_once dirname( __FILE__ ) . '/core/panel/options-framework.php';
require_once dirname( __FILE__ ) . '/options.php';
require_once dirname( __FILE__ ) . '/core/panel/options-framework-js.php';
//boxmoe.com===主题版本（语义化版本 SemVer 2.0.0）
if ( ! defined( 'BOXMOE_THEME_VERSION' ) ) {
	$boxmoe_theme_data = wp_get_theme();
	define( 'BOXMOE_THEME_VERSION', $boxmoe_theme_data->get( 'Version' ) );
}
//boxmoe.com===功能模块（集中加载清单，支持 boxmoe_modules filter 扩展/裁剪）
function boxmoe_load_modules() {
	$boxmoe_module_list = array(
		'fun-basis',
		'fun-admin',
		'fun-optimize',
		'fun-gravatar',
		'fun-navwalker',
		'fun-user',
		'fun-user-center',
		'fun-comments',
		'fun-seo',
		'fun-article',
		'fun-smtp',
		'fun-msg',
		'fun-no-category',
		'fun-shortcode',
	);
	// 子主题或扩展可通过该 filter 增删模块（保持加载顺序）
	$boxmoe_module_list = apply_filters( 'boxmoe_modules', $boxmoe_module_list );
	$boxmoe_module_dir  = get_stylesheet_directory() . '/core/module/';
	foreach ( $boxmoe_module_list as $boxmoe_module ) {
		$boxmoe_module = sanitize_file_name( $boxmoe_module );
		$boxmoe_module_file = $boxmoe_module_dir . $boxmoe_module . '.php';
		if ( file_exists( $boxmoe_module_file ) ) {
			require_once $boxmoe_module_file;
		}
	}
}
boxmoe_load_modules();
//boxmoe.com===自定义代码



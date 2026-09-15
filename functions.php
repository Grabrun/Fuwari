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
if ( ! defined( 'FUWARI_THEME_VERSION' ) ) {
	$fuwari_theme_data = wp_get_theme();
	define( 'FUWARI_THEME_VERSION', $fuwari_theme_data->get( 'Version' ) );
}
//boxmoe.com===功能模块（集中加载清单，支持 fuwari_modules filter 扩展/裁剪）
function fuwari_load_modules() {
	$fuwari_module_list = array(
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
	$fuwari_module_list = apply_filters( 'fuwari_modules', $fuwari_module_list );
	$fuwari_module_dir  = get_stylesheet_directory() . '/core/module/';
	foreach ( $fuwari_module_list as $fuwari_module ) {
		$fuwari_module = sanitize_file_name( $fuwari_module );
		$fuwari_module_file = $fuwari_module_dir . $fuwari_module . '.php';
		if ( file_exists( $fuwari_module_file ) ) {
			require_once $fuwari_module_file;
		}
	}
}
fuwari_load_modules();

// 0.3.0 选项 id 迁移：激活主题时把 boxmoe_* 旧键复制为 fuwari_* 新键（保留旧键冗余，get_fuwari 亦有读取回退）
function fuwari_migrate_legacy_options() {
	$fuwari_opt = get_option( 'options-framework-theme' );
	if ( ! is_array( $fuwari_opt ) ) {
		return;
	}
	$changed = false;
	foreach ( $fuwari_opt as $k => $v ) {
		if ( 0 === strpos( $k, 'boxmoe_' ) && ! isset( $fuwari_opt['fuwari_' . substr( $k, 7 )] ) ) {
			$fuwari_opt['fuwari_' . substr( $k, 7 )] = $v;
			$changed = true;
		}
	}
	if ( $changed ) {
		update_option( 'options-framework-theme', $fuwari_opt );
	}
}
add_action( 'after_switch_theme', 'fuwari_migrate_legacy_options' );

//boxmoe.com===自定义代码



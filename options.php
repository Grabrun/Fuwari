<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 * @copyright 2026 拿完西瓜跑 (Grabrun)
 * @license   GPL-3.0-or-later
 */

//安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}
// 0.7.0 方案C：选项定义经注册器统一加载（C1 注册制 + Schema 校验），渲染/存储层不变
require_once get_template_directory() . '/core/panel/includes/class-fuwari-options-registry.php';

function optionsframework_option_name() {
	return 'options-framework-theme';
}
function optionsframework_options() {
	// 0.5.0：双重缓存——静态缓存消除单次请求内 tabs/fields/validate 的重复构建；
	// transient 跨请求缓存（主题版本号作失效键，保存设置/分类/标签/文章变更时清理）
	static $fuwari_options_cache = null;
	if ( null !== $fuwari_options_cache ) {
		return $fuwari_options_cache;
	}
	$fuwari_cache_key = 'fuwari_options_def_' . THEME_VERSION;
	$fuwari_options_cache = get_transient( $fuwari_cache_key );
	if ( false !== $fuwari_options_cache ) {
		return $fuwari_options_cache;
	}
	// 0.7.0 方案C：定义统一由注册器加载（上下文构建、Schema 校验均在注册器内完成）
	$fuwari_options_cache = Fuwari_Options_Registry::get_all();
	// 0.5.0：写入跨请求缓存（1 小时），供下次进入设置页直接复用
	set_transient( $fuwari_cache_key, $fuwari_options_cache, HOUR_IN_SECONDS );
	return $fuwari_options_cache;
}

// 0.5.0：设置保存、分类/标签/文章变更时清空选项定义缓存
function fuwari_clear_options_cache() {
	delete_transient( 'fuwari_options_def_' . THEME_VERSION );
}
add_action( 'optionsframework_after_validate', 'fuwari_clear_options_cache' );
add_action( 'created_category', 'fuwari_clear_options_cache' );
add_action( 'edited_category', 'fuwari_clear_options_cache' );
add_action( 'delete_category', 'fuwari_clear_options_cache' );
add_action( 'created_tag', 'fuwari_clear_options_cache' );
add_action( 'edited_tag', 'fuwari_clear_options_cache' );
add_action( 'delete_tag', 'fuwari_clear_options_cache' );
add_action( 'save_post', 'fuwari_clear_options_cache' );

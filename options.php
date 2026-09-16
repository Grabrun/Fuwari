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
    //获取分类
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	//获取标签
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}
	//获取页面
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = '请选择页面';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	//定义图片路径
	$image_path =  get_template_directory_uri() . '/assets/images/';
	$web_home = 'https://www.boxmoe.com';
	$THEME_VERSION = THEME_VERSION;
	$options = array();
//基础设置-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-basis.php';
//Banner设置-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-banner.php';
//SEO优化-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-seo.php';
//文章设置-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-artice.php';
//评论设置-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-comment.php';  
//用户设置-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-user.php';
//社交图标-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-social.php';
//静态加速-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-assets.php';
//系统优化-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-optimize.php';
//消息通知-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-msg.php';
//主题信息-----------------------------------------------------------
require_once get_template_directory() . '/core/panel/settings/set-theme.php';






  
//-----------------------------------------------------------
	// 0.5.0：写入跨请求缓存（1 小时），供下次进入设置页直接复用
	set_transient( $fuwari_cache_key, $options, HOUR_IN_SECONDS );
	return $options;
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

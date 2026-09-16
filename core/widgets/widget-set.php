<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 * @copyright 2026 拿完西瓜跑 (Grabrun)
 * @license   GPL-3.0-or-later
 */
//=======安全设置，阻止直接访问主题文件=======
if (!defined('ABSPATH')) {echo'Look your sister';exit;}
//=========================================
add_action('widgets_init','unregister_d_widget');
function unregister_d_widget(){
    unregister_widget('WP_Widget_Recent_Comments');
}

$widgets = array(
	'ads',
	'postlist',
	'comments',
	'category',
	'archive',
	'tags',
	'userinfo',
	'search',

);

foreach ($widgets as $widget) {
	include 'widget-'.$widget.'.php';
}

add_action( 'widgets_init', 'widget_ui_loader' );
// 0.4.0-beta.3 修复：模块在 fuwari_load_modules() 函数内加载，顶层 $widgets 为函数局部变量而非全局；
// widget_ui_loader 在 widgets_init 时执行，global $widgets 取到 null 导致 foreach 报错且侧栏组件全部未注册。
function widget_ui_loader() {
	$fuwari_widgets = array(
		'ads',
		'postlist',
		'comments',
		'category',
		'archive',
		'tags',
		'userinfo',
		'search',
	);
	foreach ( $fuwari_widgets as $widget ) {
		$fuwari_class = 'widget_' . $widget;
		if ( class_exists( $fuwari_class ) ) {
			register_widget( $fuwari_class );
		}
	}
}

// 0.4.0-beta.4 兼容修复：0.3.0 改名时把 search/archive 两个 widget 的 id_base（WP 存储键）
// 从 boxmoe_widget_search / boxmoe_widget_archive 改成了 fuwari_*，导致升级用户的旧侧栏配置失效、
// 对应 widget 从前台侧栏消失（其余 6 个 widget id_base 未变故仍显示，即“侧栏显示了一部分”）。
// 现恢复 id_base 为 boxmoe_*；本函数把 0.3.0+ 期间产生的新键配置一次性迁移回旧键（幂等，新旧均不丢）。
function fuwari_migrate_widget_storage() {
	$fuwari_pairs = array(
		'fuwari_widget_search'  => 'boxmoe_widget_search',
		'fuwari_widget_archive' => 'boxmoe_widget_archive',
	);
	foreach ( $fuwari_pairs as $fuwari_new_id => $fuwari_old_id ) {
		$fuwari_new_opt = get_option( 'widget_' . $fuwari_new_id );
		$fuwari_old_opt = get_option( 'widget_' . $fuwari_old_id );
		$fuwari_new_has = is_array( $fuwari_new_opt ) && count( $fuwari_new_opt ) > 1;
		$fuwari_old_has = is_array( $fuwari_old_opt ) && count( $fuwari_old_opt ) > 1;
		if ( $fuwari_new_has ) {
			$fuwari_merged = $fuwari_old_has ? $fuwari_old_opt : array();
			foreach ( $fuwari_new_opt as $fuwari_k => $fuwari_v ) {
				if ( $fuwari_k === '_multiwidget' ) {
					$fuwari_merged['_multiwidget'] = $fuwari_v;
				} elseif ( ! isset( $fuwari_merged[ $fuwari_k ] ) ) {
					$fuwari_merged[ $fuwari_k ] = $fuwari_v;
				}
			}
			update_option( 'widget_' . $fuwari_old_id, $fuwari_merged );
		}
		// 侧栏引用迁移：fuwari_widget_search-N → boxmoe_widget_search-N
		$fuwari_sidebars = get_option( 'sidebars_widgets', array() );
		$fuwari_changed  = false;
		foreach ( $fuwari_sidebars as $fuwari_sb => $fuwari_wids ) {
			if ( ! is_array( $fuwari_wids ) ) {
				continue;
			}
			foreach ( $fuwari_wids as $fuwari_k => $fuwari_wid ) {
				if ( preg_match( '/^' . preg_quote( $fuwari_new_id, '/' ) . '-(\d+)$/', $fuwari_wid, $fuwari_m ) ) {
					$fuwari_sidebars[ $fuwari_sb ][ $fuwari_k ] = $fuwari_old_id . '-' . $fuwari_m[1];
					$fuwari_changed = true;
				}
			}
		}
		if ( $fuwari_changed ) {
			update_option( 'sidebars_widgets', $fuwari_sidebars );
		}
	}
}
add_action( 'init', 'fuwari_migrate_widget_storage' );
<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
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
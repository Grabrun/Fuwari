<?php
/**
 * Fuwari 选项注册器（0.7.0 方案C：定义 Schema 化 + 注册制管理）
 *
 * 职责：
 *  - C1 集中加载 11 个 set-*.php 定义文件（顺序可控，上下文统一构建）；
 *  - C2 注册制入口 Fuwari_Options_Registry::register()（声明式定义）；
 *  - Schema 校验：type 白名单、id 唯一性、group start/end 配对预检与终检（容错，仅记录不阻断渲染）；
 *  - 渲染层（class-options-interface.php 等）与选项存储（options-framework-theme）完全不变，观感保持一致。
 *
 * @package fuwari
 * @copyright 2026 拿完西瓜跑 (Grabrun)
 * @license   GPL-3.0-or-later
 */

//安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}

class Fuwari_Options_Registry {

	/** 已注册的全部定义 */
	private static $definitions = array();

	/** 是否已加载 */
	private static $loaded = false;

	/** group 配对状态（start 未闭合标记） */
	private static $group_started = false;

	/** group 配对深度（start +1 / end -1，终检须归零） */
	private static $group_depth = 0;

	/** 已注册 id 集合（唯一性告警） */
	private static $registered_ids = array();

	/** 渲染层支持的字段类型白名单 */
	private static $allowed_types = array(
		'text', 'textarea', 'select', 'radio', 'checkbox', 'multicheck',
		'upload', 'editor', 'color', 'typography', 'background',
		'heading', 'info',
	);

	/**
	 * 注册单个选项定义（C1 注册制入口）。
	 *
	 * @param array $def 选项定义数组（与旧 $options[] 完全同构）
	 */
	public static function register( array $def ) {
		self::$definitions[] = $def;

		// C2 Schema 校验（容错：仅记录，不阻断渲染，保证设置页不白屏）
		if ( isset( $def['type'] ) ) {
			if ( ! in_array( $def['type'], self::$allowed_types, true ) ) {
				self::log( '未知 type「' . $def['type'] . '」' );
			}
		} elseif ( empty( $def['group'] ) ) {
			self::log( '定义缺少 type 且无 group 标记' );
		}
		if ( isset( $def['id'] ) ) {
			if ( isset( self::$registered_ids[ $def['id'] ] ) ) {
				self::log( '重复 id「' . $def['id'] . '」' );
			}
			self::$registered_ids[ $def['id'] ] = true;
		}
		if ( isset( $def['group'] ) ) {
			if ( 'start' === $def['group'] ) {
				if ( self::$group_started ) {
					self::log( 'group start 未闭合（前一组缺 end）' );
				}
				self::$group_started = true;
				self::$group_depth++;
			} elseif ( 'end' === $def['group'] ) {
				if ( ! self::$group_started ) {
					self::log( 'group end 无对应 start（孤儿 end）' );
				}
				self::$group_started = false;
				self::$group_depth--;
			}
		}
	}

	/**
	 * 一次性加载全部定义文件（保持上下文变量对定义文件可见）。
	 */
	public static function load() {
		if ( self::$loaded ) {
			return;
		}
		self::$loaded = true;

		// C1：统一构建上下文（定义文件引用的数据，与旧 options.php 一致）
		$options_categories = array();
		$options_categories_obj = get_categories();
		foreach ( $options_categories_obj as $category ) {
			$options_categories[ $category->cat_ID ] = $category->cat_name;
		}
		$options_tags = array();
		$options_tags_obj = get_tags();
		foreach ( $options_tags_obj as $tag ) {
			$options_tags[ $tag->term_id ] = $tag->name;
		}
		$options_pages = array();
		$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
		$options_pages[''] = '请选择页面';
		foreach ( $options_pages_obj as $page ) {
			$options_pages[ $page->ID ] = $page->post_title;
		}
		$image_path = get_template_directory_uri() . '/assets/images/';
		$web_home = 'https://www.boxmoe.com';
		$THEME_VERSION = THEME_VERSION;

		$dir = get_template_directory() . '/core/panel/settings/';
		foreach ( array( 'basis', 'banner', 'seo', 'artice', 'comment', 'user', 'social', 'assets', 'optimize', 'msg', 'theme' ) as $set ) {
			$file = $dir . 'set-' . $set . '.php';
			if ( file_exists( $file ) ) {
				// 定义文件内通过 Fuwari_Options_Registry::register() 注册（include 继承当前作用域变量）
				include $file;
			} else {
				self::log( '缺少定义文件 set-' . $set . '.php' );
			}
		}

		// 加载完成后的 group 配对终检
		if ( 0 !== self::$group_depth ) {
			self::log( 'group 配对不平衡（depth=' . self::$group_depth . '）' );
		}
	}

	/**
	 * 返回全部定义（渲染/校验/缓存共用入口）。
	 */
	public static function get_all() {
		self::load();
		return self::$definitions;
	}

	/**
	 * 静默日志（仅 WP_DEBUG 时记录，不影响前台输出）。
	 */
	private static function log( $msg ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[Fuwari Options] ' . $msg );
		}
	}
}

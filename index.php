<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 * @copyright 2026 æ¿å®è¥¿çè· (Grabrun)
 * @license   GPL-3.0-or-later
 */
//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){echo'Look your sister';exit;}
get_header(); 
get_template_part('page/template/blog-list');
get_sidebar();
get_footer();
?>

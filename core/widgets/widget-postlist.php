<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 * @copyright 2026 æ¿å®è¥¿çè· (Grabrun)
 * @license   GPL-3.0-or-later
 */
//=======安全设置，阻止直接访问主题文件=======
if (!defined('ABSPATH')) {echo'Look your sister';exit;}
//=========================================
class widget_postlist extends WP_Widget {

	function __construct(){
		parent::__construct( 'widget_postlist', 'Fuwari_侧栏文章', array( 'description' => __('Fuwari_文章侧栏', 'text_domain'),
				  'classname' => __('widget-postlist', 'text_domain' )) );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title     = apply_filters('widget_name', $instance['title']);
		$limit     = isset($instance['limit']) ? $instance['limit'] : 6;
		$cat       = isset($instance['cat']) ? $instance['cat'] : 0;
		$orderby   = isset($instance['orderby']) ? $instance['orderby'] : 'date';
		$showstyle = isset($instance['showstyle']) ? $instance['showstyle'] : 'widget-content';
		// $img = $instance['img'];
		$style = ' class="'.$showstyle.'"';
		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<div'.$style.'>';
		$args = array(
			'order'            => 'DESC',
			'cat'              => $cat,
			'orderby'          => $orderby,
			'showposts'        => $limit,
			'ignore_sticky_posts' => 1
		);
		query_posts($args);
		while (have_posts()) : the_post(); 		
		echo '<article class="widget-post">
		               <div class="info">
                        <a href="'. get_the_permalink() .'" '. fuwari_article_new_window() .' class="thumb">
                          <span class="fullimage" style="background-image: url('.fuwari_article_thumbnail_src(fuwari_random_string(6)).');"></span>
                        </a>
                        <div class="right">
                          <h4 class="title">
                            <a '. fuwari_article_new_window() .' href="'. get_the_permalink() .'">'. get_the_title() . get_the_subtitle() .'</a></h4>
                          <time datetime="'.get_the_time('Y-m-d').'">'.get_the_time('Y-m-d').'</time>
						  </div>';
		echo '</article>';					
		endwhile; wp_reset_query();
		echo '</div>';
		echo $after_widget;
	}

	function form( $instance ) {
		$defaults = array( 
			'title' => __('最新文章', 'fuwari-com'), 
			'limit' => 6, 
			'cat' => '', 
			'orderby' => 'date', 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label>
				<?php echo __('标题：', 'fuwari-com') ?>
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				<?php echo __('排序：', 'fuwari-com') ?>
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>><?php echo __('评论数', 'fuwari-com') ?></option>
					<option value="date" <?php selected('date', $instance['orderby']); ?>><?php echo __('发布时间', 'fuwari-com') ?></option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>><?php echo __('随机', 'fuwari-com') ?></option>
				</select>
			</label>
		</p>
		<p>
			<label>
				<?php echo __('分类限制：', 'fuwari-com') ?>
				<a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="<?php echo __('格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的', 'fuwari-com') ?>">？</a>
				<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<?php echo __('显示数目：', 'fuwari-com') ?>
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		
	<?php
	}
}

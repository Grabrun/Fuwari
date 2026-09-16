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
class widget_ads extends WP_Widget {

	function __construct(){
		parent::__construct( 'widget_ads', 'Fuwari_广告侧栏', array( 'classname' => 'widget_ads' ) );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$code = $instance['code'];
		echo $before_widget;
		echo '<H4 class="widget-title">'.$title.'</H4>';
		echo '<div class="widget_ads_inner">'.$code.'</div>';
		echo $after_widget;
	}
	function form($instance) {
		$defaults = array( 
			'title' => __('广告', 'fuwari-com').' '.date('m-d'), 
			'code' => '' 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label>
				<?php echo __('标题：', 'fuwari-com') ?>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<?php echo __('广告代码：', 'fuwari-com') ?>
				<textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $instance['code']; ?></textarea>
			</label>
		</p>
<?php
	}
}

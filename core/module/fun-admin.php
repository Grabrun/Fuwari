<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 * @copyright 2026 拿完西瓜跑 (Grabrun)
 * @license   GPL-3.0-or-later
 */

// 安全设置--------------------------boxmoe.com--------------------------
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}

// 设置菜单--------------------------boxmoe.com--------------------------
function fuwari_options_menu_filter($menu) {
	$menu['mode'] = 'menu';
	$menu['page_title'] = 'Fuwari主题设置';
	$menu['menu_title'] = 'Fuwari主题设置';
	$menu['menu_slug'] = 'fuwari-options';
	$menu['icon_url'] = 'dashicons-admin-generic';
	$menu['position'] = '61';
	return $menu;
}
add_filter('optionsframework_menu', 'fuwari_options_menu_filter');

//编辑器TinyMCE增强
function enable_more_buttons($buttons)
{
	$buttons[] = 'fuwari_emoji';
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");

// 添加自定义表情按钮
function add_fuwari_emoji_button($plugins) {
	$plugins['fuwari_emoji'] = get_template_directory_uri() . '/assets/js/tinymce-emoji.js';
	return $plugins;
}
add_filter('mce_external_plugins', 'add_fuwari_emoji_button');
function fuwari_tinymce_emoji($init) {
	$emoji_list = array(
		'😀' => '笑脸',
		'😂' => '笑哭',
		'😍' => '爱心眼',
		'🤔' => '思考',
		'😊' => '害羞',
		'👍' => '点赞',
		'🎉' => '庆祝',
		'❤️' => '红心',
		'😎' => '酷',
		'😴' => '睡觉',
		'😭' => '大哭',
		'😡' => '生气',
		'🤮' => '呕吐',
		'🥳' => '派对',
		'😱' => '惊恐',
		'🤣' => '笑倒',
		'🥰' => '喜欢',
		'😇' => '天使',
		'🤩' => '星星眼',
		'🤪' => '搞怪'
	);	
	$init['fuwari_emoji_list'] = json_encode($emoji_list, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE);
	return $init;
}
add_filter('tiny_mce_before_init', 'fuwari_tinymce_emoji');

// 添加HTML编辑器表情按钮
function fuwari_html_editor_emoji() {
	echo '<div class="quicktags-toolbar-emoji">
		<strong>表情：</strong>
		<button type="button" class="emoji-btn" data-emoji="😀" title="笑脸">😀</button>
		<button type="button" class="emoji-btn" data-emoji="😂" title="笑哭">😂</button>
		<button type="button" class="emoji-btn" data-emoji="😍" title="爱心眼">😍</button>
		<button type="button" class="emoji-btn" data-emoji="🤔" title="思考">🤔</button>
		<button type="button" class="emoji-btn" data-emoji="😊" title="害羞">😊</button>
		<button type="button" class="emoji-btn" data-emoji="👍" title="点赞">👍</button>
		<button type="button" class="emoji-btn" data-emoji="🎉" title="庆祝">🎉</button>
		<button type="button" class="emoji-btn" data-emoji="❤️" title="红心">❤️</button>
		<button type="button" class="emoji-btn" data-emoji="😎" title="酷">😎</button>
		<button type="button" class="emoji-btn" data-emoji="😴" title="睡觉">😴</button>
		<button type="button" class="emoji-btn" data-emoji="😭" title="大哭">😭</button>
		<button type="button" class="emoji-btn" data-emoji="😡" title="生气">😡</button>
		<button type="button" class="emoji-btn" data-emoji="🤮" title="呕吐">🤮</button>
		<button type="button" class="emoji-btn" data-emoji="🥳" title="派对">🥳</button>
		<button type="button" class="emoji-btn" data-emoji="😱" title="惊恐">😱</button>
		<button type="button" class="emoji-btn" data-emoji="🤣" title="笑倒">🤣</button>
		<button type="button" class="emoji-btn" data-emoji="🥰" title="喜欢">🥰</button>
		<button type="button" class="emoji-btn" data-emoji="😇" title="天使">😇</button>
		<button type="button" class="emoji-btn" data-emoji="🤩" title="星星眼">🤩</button>
		<button type="button" class="emoji-btn" data-emoji="🤪" title="搞怪">🤪</button>
	</div>
	<style>
.quicktags-toolbar-emoji{background:#f5f5f5;padding:5px;border-radius:3px;margin:5px 0;position:relative;z-index:100;clear:both;display:block;}.emoji-btn{padding:4px 8px;margin:0 2px;border:1px solid #ddd;background:#fff;border-radius:3px;cursor:pointer;}.emoji-btn:hover{background:#f0f0f0;}#wp-content-editor-container{margin-top:5px;}
	</style>
	<script>
		jQuery(document).ready(function($) {
			var $toolbar = $(".quicktags-toolbar-emoji");
			var $editorToolbar = $("#wp-content-editor-tools");
			if($editorToolbar.length) {
				$toolbar.insertAfter($editorToolbar);
			}
			
			$(".emoji-btn").click(function() {
				var emoji = $(this).data("emoji");
				var textarea = $("#content");
				var caretPos = textarea[0].selectionStart;
				var textAreaTxt = textarea.val();
				textarea.val(textAreaTxt.substring(0, caretPos) + emoji + textAreaTxt.substring(caretPos));
			});
		});
	</script>';
}
add_action('edit_form_after_title', 'fuwari_html_editor_emoji');

// 评论回复编辑器上添加表情按钮
function add_comment_emoji_buttons() {
    $screen = get_current_screen();
    if ($screen->id !== 'edit-comments') {
        return;
    }
    echo '<div class="quicktags-toolbar-emoji comment-emoji-toolbar">
        <strong>表情：</strong>
        <button type="button" class="emoji-btn" data-emoji="😀" title="笑脸">😀</button>
        <button type="button" class="emoji-btn" data-emoji="😂" title="笑哭">😂</button>
        <button type="button" class="emoji-btn" data-emoji="😍" title="爱心眼">😍</button>
        <button type="button" class="emoji-btn" data-emoji="🤔" title="思考">🤔</button>
        <button type="button" class="emoji-btn" data-emoji="😊" title="害羞">😊</button>
        <button type="button" class="emoji-btn" data-emoji="👍" title="点赞">👍</button>
        <button type="button" class="emoji-btn" data-emoji="🎉" title="庆祝">🎉</button>
        <button type="button" class="emoji-btn" data-emoji="❤️" title="红心">❤️</button>
        <button type="button" class="emoji-btn" data-emoji="😎" title="酷">😎</button>
        <button type="button" class="emoji-btn" data-emoji="😴" title="睡觉">😴</button>
        <button type="button" class="emoji-btn" data-emoji="😭" title="大哭">😭</button>
        <button type="button" class="emoji-btn" data-emoji="😡" title="生气">😡</button>
        <button type="button" class="emoji-btn" data-emoji="🤮" title="呕吐">🤮</button>
        <button type="button" class="emoji-btn" data-emoji="🥳" title="派对">🥳</button>
        <button type="button" class="emoji-btn" data-emoji="😱" title="惊恐">😱</button>
        <button type="button" class="emoji-btn" data-emoji="🤣" title="笑倒">🤣</button>
        <button type="button" class="emoji-btn" data-emoji="🥰" title="喜欢">🥰</button>
        <button type="button" class="emoji-btn" data-emoji="😇" title="天使">😇</button>
        <button type="button" class="emoji-btn" data-emoji="🤩" title="星星眼">🤩</button>
        <button type="button" class="emoji-btn" data-emoji="🤪" title="搞怪">🤪</button>
    </div>
    <style>
.comment-emoji-toolbar{margin:5px 0;padding:5px;background:#f5f5f5;border-radius:3px;}.comment-emoji-toolbar .emoji-btn{padding:4px 8px;margin:0 2px;border:1px solid #ddd;background:#fff;border-radius:3px;cursor:pointer;}.comment-emoji-toolbar .emoji-btn:hover{background:#f0f0f0;}
    </style>
    <script>
        jQuery(document).ready(function($) {
            initEmojiButtons();        
            $(document).on("click", ".reply", function() {
                setTimeout(function() {
                    var $replyRow = $("#replyrow");
                    if ($replyRow.find(".comment-emoji-toolbar").length === 0) {
                        var $toolbar = $(".comment-emoji-toolbar").first().clone();
                        $replyRow.find("#replycontainer").prepend($toolbar);
                        initEmojiButtons();
                    }
                }, 100);
            });        
            function initEmojiButtons() {
                $(".comment-emoji-toolbar .emoji-btn").off("click").on("click", function() {
                    var emoji = $(this).data("emoji");
                    var $textarea = $(this).closest("tr, #replyrow").find("textarea");
                    var caretPos = $textarea[0].selectionStart;
                    var textAreaTxt = $textarea.val();
                    $textarea.val(textAreaTxt.substring(0, caretPos) + emoji + textAreaTxt.substring(caretPos));
                });
            }
        });
    </script>';
}
add_action('admin_footer', 'add_comment_emoji_buttons');

function fuwari_admin_style() {
    echo '<style>
		.avatar{width:60px;height:60px;}.comment-emoji-toolbar{margin-bottom:10px;}.quicktags-toolbar input[type="button"]{margin:2px !important;padding:2px 8px !important;border-radius:3px !important;border:1px solid #ddd !important;background:#f7f7f7 !important;color:#666 !important;transition:all 0.3s ease;}.quicktags-toolbar input[type="button"]:hover{background:#0073aa !important;color:#fff !important;border-color:#006799 !important;}.quicktags-toolbar input[type="button"] i{margin-right:4px;}
    </style>';
}
add_action('admin_head', 'fuwari_admin_style');

function example_footer_admin () {
	echo '<span id="footer-thankyou">感谢使用<a target="_blank" href="https://cn.wordpress.org/">WordPress</a>进行创作。Theme by 拿完西瓜跑</span> ';
	}
	add_filter('admin_footer_text', 'example_footer_admin');

function customize_login_logo(){         
echo '<style type="text/css">
.login{display:flex;min-height:100vh;justify-content:center;align-items:center;background:linear-gradient(-45deg,#ee7752,#e73c7e,#23a6d5,#23d5ab);background-size:400% 400%;animation:gradient 15s ease infinite;}@keyframes gradient{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}#login{background:rgba(255,255,255,0.9);padding:40px 30px;border-radius:15px;box-shadow:0 0 20px rgba(0,0,0,0.1);width:350px;}@media (max-width:768px){#login{background:transparent;box-shadow:none;}}.login h1 a{background-image:url('.get_template_directory_uri() .'/assets/images/logo.png);width:180px;height:80px;margin:0 auto 20px;background-size:contain;background-repeat:no-repeat;background-position:center center;}.login form{background:transparent !important;padding:0 !important;border:none !important;box-shadow:none !important;}.login input[type="text"],.login input[type="password"]{border-radius:5px;border:1px solid #ddd;padding:10px;margin-bottom:15px;}.wp-core-ui .button-primary{background:#23a6d5;border:none;border-radius:5px;padding:5px 20px;height:auto;transition:all 0.3s ease;}.wp-core-ui .button-primary:hover{background:#1e8ab0;}.language-switcher{display:none;}
</style>';   
}  
add_action('login_head', 'customize_login_logo'); 	
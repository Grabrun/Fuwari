<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 */

// 安全设置--------------------------boxmoe.com--------------------------
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}

// 常量定义--------------------------boxmoe.com--------------------------
$themedata = wp_get_theme();
$themeversion = $themedata['Version'];
define('THEME_VERSION', $themeversion);


// 随机字符串--------------------------boxmoe.com--------------------------
function fuwari_random_string($length = 6) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}


// 主题静态资源url--------------------------boxmoe.com--------------------------
function fuwari_theme_url(){
    if(get_fuwari('fuwari_cdn_assets_switch')){

        return get_fuwari('fuwari_cdn_assets_url') ?: get_template_directory_uri();
    }
    return get_template_directory_uri();
}

// 前端布局--------------------------boxmoe.com--------------------------
function fuwari_layout_setting(){
    $layout = get_fuwari('fuwari_blog_layout');
    if($layout){
        if($layout == 'one'){
            echo 'col-lg-10 mx-auto';
        }elseif($layout == 'two'){
            echo 'col-lg-8';
        }
    }else{
        echo 'col-lg-10 mx-auto';
    }
}

// Favicon--------------------------boxmoe.com--------------------------
function fuwari_favicon(){
    $src= get_fuwari('fuwari_favicon_src');    
    if($src){
        echo $src;
    }else{
        echo fuwari_theme_url().'/assets/images/favicon.ico';
    }
}

// LOGO--------------------------boxmoe.com--------------------------
function fuwari_logo(){
    $src= get_fuwari('fuwari_logo_src');    

    if($src){
        echo '<img class="logo" src="'.$src.'" alt="'.get_bloginfo('name').'">';
    }else{
        echo '<span class="text-inverse">'.get_bloginfo('name').'</span>';
    }
}

// Banner图片--------------------------boxmoe.com--------------------------
function fuwari_banner_image(){
    $src='';
    if(get_fuwari('fuwari_banner_api_switch')){
        $src= get_fuwari('fuwari_banner_api_url');    
    }elseif(get_fuwari('fuwari_banner_rand_switch')){
        $random_images = glob(get_template_directory().'/assets/images/banner/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);   
        if (!empty($random_images)) {
            $random_key = array_rand($random_images);
            $relative_path = str_replace(get_template_directory(), '', $random_images[$random_key]);
            $src = fuwari_theme_url() . $relative_path;
        }
    }elseif(get_fuwari('fuwari_banner_url')){
        $src= get_fuwari('fuwari_banner_url');
    }else{
        $src= fuwari_theme_url().'/assets/images/banner.jpg';
    }
    echo $src;
}

// 节日灯笼--------------------------boxmoe.com--------------------------
function fuwari_festival_lantern(){
    if(get_fuwari('fuwari_festival_lantern_switch')){?>
    <div id="wp"class="wp"><div class="xnkl"><div class="deng-box2"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo get_fuwari('fuwari_lanternfont2','度')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box3"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo get_fuwari('fuwari_lanternfont1','欢')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box1"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo get_fuwari('fuwari_lanternfont4','春')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div><div class="deng-box"><div class="deng"><div class="xian"></div><div class="deng-a"><div class="deng-b"><div class="deng-t"><?php echo get_fuwari('fuwari_lanternfont3','新')?></div></div></div><div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div></div></div></div></div>
    <?php
    }
}

// 高度载入--------------------------boxmoe.com--------------------------
function fuwari_banner_height_load(){
        $pc_height = get_fuwari('fuwari_banner_height') ?: '580';
        $mb_height = get_fuwari('fuwari_banner_height_mobile') ?: '480';
        echo "<style>.fuwari_header_banner{height:{$pc_height}px;} @media (max-width: 768px){.fuwari_header_banner{height:{$mb_height}px;}}</style>"."\n    ";
}


// 全站变灰--------------------------boxmoe.com--------------------------
function fuwari_body_grey(){
    if(get_fuwari('fuwari_body_grey_switch')){
        $css = "body{filter: grayscale(100%);}";
        wp_add_inline_style('fuwari-style', $css);
    }
}
// 欢迎语--------------------------boxmoe.com--------------------------
function fuwari_banner_welcome(){
    echo get_fuwari('fuwari_banner_font')?:'Hello! 欢迎来到盒子萌！';
}


// 欢迎语一言 --------------------------boxmoe.com--------------------------
function fuwari_banner_hitokoto(){
    if(get_fuwari('fuwari_banner_hitokoto_switch')){
        echo '<h1 class="main-title"><i class="fa fa-star spinner"></i><span id="hitokoto" class="text-gradient">加载中</span></h1>';
    }
}


// 前端资源载入--------------------------boxmoe.com--------------------------
function fuwari_load_assets_header(){ 
    wp_enqueue_style('theme-style', fuwari_theme_url() . '/assets/css/theme.min.css', array(), THEME_VERSION);
    wp_enqueue_style('fuwari-style', fuwari_theme_url() . '/assets/css/style.css', array(), THEME_VERSION);
    if(get_fuwari('fuwari_jquery_switch')){
        wp_enqueue_script('jquery-script', fuwari_theme_url() . '/assets/js/jquery.min.js', array(), THEME_VERSION, true);
    }
    wp_enqueue_script('theme-script', fuwari_theme_url() . '/assets/js/theme.min.js', array(), THEME_VERSION, true);
    wp_enqueue_script('theme-lib-script', fuwari_theme_url() . '/assets/js/lib.min.js', array(), THEME_VERSION, true);
    wp_enqueue_script('comments-script', fuwari_theme_url() . '/assets/js/comments.js', array(), THEME_VERSION, true);
    wp_enqueue_script('fuwari-script', fuwari_theme_url() . '/assets/js/fuwari.js', array(), THEME_VERSION, true);
    if(get_fuwari('fuwari_sakura_switch')){
        wp_enqueue_script('sakura-script', fuwari_theme_url() . '/assets/js/sakura.js', array(), THEME_VERSION, true);
    }

    wp_localize_script('theme-script', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'themeurl' => fuwari_theme_url(),
        'is_user_logged_in' => is_user_logged_in() ? 'true' : 'false',
        'posts_per_page' => get_option('posts_per_page'),
        'nonce' =>wp_create_nonce('fuwari_ajax_nonce'),
        'running_days' => get_fuwari('fuwari_footer_running_days_time')?:'2025-01-01',
        'hitokoto' => get_fuwari('fuwari_banner_hitokoto_text')?:'a'
    ));
}
add_action('wp_enqueue_scripts', 'fuwari_load_assets_header');
add_action('wp_enqueue_scripts', 'fuwari_body_grey', 12);

// 前端内容载入--------------------------boxmoe.com--------------------------
function fuwari_load_assets_footer(){?>
          <div class="col-md-4 text-center text-md-start">
            <a class="mb-2 mb-lg-0 d-block" href="<?php echo home_url(); ?>">
            <?php fuwari_logo(); ?></a>
          </div>
          <div class="col-md-8 col-lg-4 ">
            <div class="small mb-3 mb-lg-0 text-center">
                <?php if(get_fuwari('fuwari_footer_seo')): ?>
                    <ul class="nav flex-row align-items-center mt-sm-0 justify-content-center nav-footer">
                        <?php echo get_fuwari('fuwari_footer_seo');?>
                    </ul>
                <?php endif; ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex align-items-center justify-content-center justify-content-md-end" id="social-links">
              <div class="text-center text-md-end">
                <?php if(get_fuwari('fuwari_social_instagram')): ?>
                <a href="<?php echo get_fuwari('fuwari_social_instagram'); ?>" class="text-reset btn btn-social btn-instagram" target="_blank">
                  <i class="fa fa-instagram"></i>
                </a>
                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_telegram')): ?>
                <a href="<?php echo get_fuwari('fuwari_social_telegram'); ?>" class="text-reset btn btn-social btn-telegram" target="_blank">
                  <i class="fa fa-telegram"></i>
                </a>
                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_github')): ?>
                <a href="<?php echo get_fuwari('fuwari_social_github'); ?>" class="text-reset btn btn-social btn-github" target="_blank">
                  <i class="fa fa-github"></i>
                </a>
                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_qq')): ?>
                <a href="https://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo get_fuwari('fuwari_social_qq'); ?>&amp;site=qq&amp;menu=yes" class="text-reset btn btn-social btn-qq" target="_blank">
                  <i class="fa fa-qq"></i>
                </a>

                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_wechat')): ?>
                <a href="<?php echo get_fuwari('fuwari_social_wechat'); ?>" data-fancybox class="text-reset btn btn-social btn-wechat">
                  <i class="fa fa-weixin"></i>
                </a>
                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_weibo')): ?>
                <a href="<?php echo get_fuwari('fuwari_social_weibo'); ?>" class="text-reset btn btn-social btn-weibo" target="_blank">
                  <i class="fa fa-weibo"></i>
                </a>
                <?php endif; ?>
                <?php if(get_fuwari('fuwari_social_email')): ?>
                <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=<?php echo get_fuwari('fuwari_social_email'); ?>" class="text-reset btn btn-social btn-email" target="_blank">
                  <i class="fa fa-envelope"></i>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-lg-12 text-center mt-3 copyright">
          <span>Copyright © <?php echo date('Y'); ?> <a href="<?php echo home_url(); ?>"><?php echo get_bloginfo('name'); ?></a> <?php echo get_fuwari('fuwari_footer_info','Powered by WordPress'); ?> </span>
          <span>Theme by <a href="https://www.boxmoe.com" target="_blank" rel="noopener">Fuwari（浮絮）</a> · 源自 <a href="https://www.boxmoe.com" target="_blank" rel="noopener">LoliMeow</a> 项目</span>
          <?php if(get_fuwari('fuwari_footer_running_days_switch')): ?> 
          <?php echo get_fuwari('fuwari_footer_running_days_prefix','本站已稳定运行了'); ?><span id="running-days" style="display:inline-block;">0</span><?php echo get_fuwari('fuwari_footer_running_days_suffix','天'); ?>
          <?php endif; ?>
          <?php if(get_fuwari('fuwari_footer_dataquery_switch')): ?>
          <span><?php echo get_num_queries(); ?> queries in <?php echo timer_stop(0,3); ?> s</span>
          <?php endif; ?>
          <span style="display:none;"><?php echo get_fuwari('fuwari_trackcode'); ?></span>
           </div>
<?php
}


// 注册导航菜单--------------------------boxmoe.com--------------------------
function fuwari_register_menus() {
    register_nav_menus([
        'fuwari-menu' => __('主导航菜单', 'fuwari')
    ]);
}
add_action('after_setup_theme', 'fuwari_register_menus');


// 导航菜单--------------------------boxmoe.com--------------------------
function fuwari_nav_menu(){
    $menu_args = [
        'theme_location' => 'fuwari-menu',
        'container' => false,
        'menu_class' => 'navbar-nav mx-auto align-items-lg-center',
        'walker' => new bootstrap_5_wp_nav_menu_walker(),
        'depth' => 3,
        'fallback_cb' => false
    ];
    if (has_nav_menu('fuwari-menu')) {
        wp_nav_menu($menu_args);
    } else {
        echo '<div class="navbar-nav mx-auto align-items-lg-center">请先在后台创建并分配菜单</div>';
    }
}

// 侧栏模块--------------------------boxmoe.com--------------------------
if(get_fuwari('fuwari_blog_layout') == 'two'){
    if (function_exists('register_sidebar')){
        $widgets = array(
            'site_sidebar' => __('全站侧栏展示', 'fuwari-com'),
            'home_sidebar' => __('首页侧栏展示', 'fuwari-com'),
            'post_sidebar' => __('文章页侧栏展示', 'fuwari-com'),
            'page_sidebar' => __('页面侧栏展示', 'fuwari-com'),
        );
		$fuwari_border='';
		if(get_fuwari('fuwari_blog_border') == 'default' ){
			$fuwari_border='';
			}elseif(get_fuwari('fuwari_blog_border') == 'border'){
			$fuwari_border='blog-border';
			}elseif(get_fuwari('fuwari_blog_border') == 'shadow'){
			$fuwari_border='blog-shadow';
            }elseif(get_fuwari('fuwari_blog_border') == 'lines'){
            $fuwari_border='blog-lines';
            }

        foreach ($widgets as $key => $value) {
            register_sidebar(array(
                'name'          => $value,
                'id'            => 'widget_'.$key,
                'before_widget' => '<div class="widget '.$fuwari_border.' %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>'
            ));
        }
    }
    require_once get_template_directory() . '/core/widgets/widget-set.php';
}



// 懒加载图片--------------------------boxmoe.com--------------------------
function fuwari_lazy_load_images(){
    if(get_fuwari('fuwari_lazy_load_images')){
        $src = get_fuwari('fuwari_lazy_load_images');
    }else{
        $src = fuwari_theme_url().'/assets/images/loading.gif';
    }
    return $src;
}


// 边框设置--------------------------boxmoe.com--------------------------
function fuwari_border_setting(){
    $border = get_fuwari('fuwari_blog_border');
    if($border){
        if($border == 'default'){
            echo '';
        }elseif($border == 'border'){
            echo 'blog-border';
        }elseif($border == 'shadow'){
            echo 'blog-shadow';
        }elseif($border == 'lines'){
            echo 'blog-lines';
        }
    }else{
        echo 'blog-border';
    }
}



// 搜索结果排除所有页面--------------------------boxmoe.com--------------------------
function fuwari_search_exclude_pages($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts', 'fuwari_search_exclude_pages');


// 开启友情链接--------------------------boxmoe.com--------------------------
add_filter( 'pre_option_link_manager_enabled', '__return_true' );



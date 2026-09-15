<?php
/**
 * Template Name: 用户中心
 * @link https://www.boxmoe.com
 * @package lolimeow
 */
//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){echo'Look your sister';exit;}
//如果用户已经登陆那么跳转到首页
if (!is_user_logged_in()){
    wp_safe_redirect( get_option('home') );
    exit;
 }
get_header();
global $wpdb,$current_user;
$user_info=wp_get_current_user();

echo '<link rel="stylesheet" href="'.boxmoe_theme_url().'/assets/css/user_center.css">';
$items = isset($_GET["items"]) ? $_GET["items"] : 'home';
$current_user = wp_get_current_user();
?>
    <section class="py-lg-7 py-5 user_center">
         <div class="container">
            <div class="row">
                <?php require_once(get_template_directory() . '/page/template/user-nav.php'); ?>
                <div class="col-lg-9 col-md-8">
                  <div class="card border-0 mb-4 shadow-sm">
                     <div class="card-body p-lg-5">
                        <div class="mb-3 d-lg-flex align-items-center justify-content-between">
                            <h3><?php
                            $menu_names = array(
                                'home' => '个人中心',
                                'collect' => '我的收藏',
                                'comment' => '我的评论',
                                'password' => '修改密码'
                            );
                            echo isset($menu_names[$items]) ? $menu_names[$items] : $items;
                            ?></h3>
                        </div>
                        <div class="table-responsive mb-3">
                              <table class="table table-centered td table-centered th table-lg text-nowrap">
                                 <tbody>
                                    <tr>
                                       <th scope="row">
                                          <div class="d-flex align-items-center">
                                          <img id="user-avatar"  src="<?php echo boxmoe_get_avatar_url($current_user->ID,100); ?>"  class="avatar rounded-3 img-fluid" alt="avatar">
                                             <div class="ms-3">
                                                <div class="fs-5 fw-semibold text-dark"><?php echo get_user_meta(get_current_user_id(), 'nickname', true); ?> (ID:<?php echo $current_user->ID; ?>)</div>
                                             </div>
                                             <div class="ms-3">
                                                <div class="fs-6 fw-semibold text-dark">最近登录</div>
                                                <small><?php echo get_user_meta(get_current_user_id(), 'last_login_time', true); ?></small>
                                             </div>
                                             <div class="ms-3">
                                                <div class="fs-6 fw-semibold text-dark">登录IP</div>
                                                <small><?php echo get_user_meta(get_current_user_id(), 'last_login_ip', true); ?></small>
                                             </div>
                                          </div>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                        </div>
                <?php if($items == 'home'){?>
                    <?php require_once(get_template_directory() . '/page/template/user-home.php'); ?>
                <?php }elseif($items == 'collect'){?>
                    <?php require_once(get_template_directory() . '/page/template/user-collect.php'); ?>
                <?php }elseif($items == 'comment'){?>
                    <?php require_once(get_template_directory() . '/page/template/user-comment.php'); ?>
                <?php }elseif($items == 'password'){?>
                    <?php require_once(get_template_directory() . '/page/template/user-password.php'); ?>
                <?php }else{?>
                    <?php require_once(get_template_directory() . '/page/template/user-home.php'); ?>
                <?php }?>
                </div>
                  </div>
               </div>

            </div>
         </div>
      </section>
<?php
get_footer();
?>
<script type="text/javascript" src="<?php echo boxmoe_theme_url(); ?>/assets/js/user_center.js"></script>

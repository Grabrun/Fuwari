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

// 用户中心链接设置--------------------------boxmoe.com--------------------------
function fuwari_user_center_link_page(){
    $fuwari_user_center_link_page = get_fuwari('fuwari_user_center_link_page');
    if($fuwari_user_center_link_page){
        return get_the_permalink($fuwari_user_center_link_page);
    }else{
        return false;
    }
}

// 注册页面链接设置--------------------------boxmoe.com--------------------------
function fuwari_sign_up_link_page(){
    $fuwari_sign_up_link_page = get_fuwari('fuwari_sign_up_link_page');
    if($fuwari_sign_up_link_page){
        return get_the_permalink($fuwari_sign_up_link_page);
    }else{
        return false;
    }
}


// 登录页面链接设置--------------------------boxmoe.com--------------------------
function fuwari_sign_in_link_page(){
     $fuwari_sign_in_link_page = get_fuwari('fuwari_sign_in_link_page');
    if($fuwari_sign_in_link_page){
        return get_the_permalink($fuwari_sign_in_link_page);
    }else{
        return false;
    }
}

// 重置密码页面链接设置--------------------------boxmoe.com--------------------------
function fuwari_reset_password_link_page(){
    $fuwari_reset_password_link_page = get_fuwari('fuwari_reset_password_link_page');
    if($fuwari_reset_password_link_page){
        return get_the_permalink($fuwari_reset_password_link_page);
    }else{
        return false;
    }
}

add_action('wp_ajax_nopriv_user_login_action', 'handle_user_login');
add_action('wp_ajax_user_login_action', 'handle_user_login');

function handle_user_login() {
    $formData = json_decode(stripslashes($_POST['formData']), true);
    if (!isset($formData['login_nonce']) || !wp_verify_nonce($formData['login_nonce'], 'user_login')) {
        wp_send_json_error(array(
            'message' => '安全验证失败，请刷新页面重试'
        ));
        exit;
    }  
    if (empty($formData['username']) || empty($formData['password'])) {
        wp_send_json_error(array(
            'message' => '用户名和密码不能为空'
        ));
        exit;
    }
    
    $username = sanitize_text_field($formData['username']);
    $password = $formData['password'];
    $remember = isset($formData['rememberme']) ? true : false;
    
    if (is_email($username)) {
        $user = get_user_by('email', $username);
        if ($user) {
            $username = $user->user_login;
        }
    }
    
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => $remember
    );
    
    $user = wp_signon($creds, false);
    
    if (is_wp_error($user)) {
        $error_code = $user->get_error_code();
        $error_message = '';
        
        switch ($error_code) {
            case 'invalid_username':
                $error_message = '用户不存在，如果不确定可以用邮箱登录';
                break;
            case 'incorrect_password':
                $error_message = '密码错误';
                break;
            case 'empty_username':
                $error_message = '请输入用户名';
                break;
            case 'empty_password':
                $error_message = '请输入密码';
                break;
            default:
                $error_message = '登录失败，请检查用户名和密码';
        }
        
        wp_send_json_error(array(
            'message' => $error_message
        ));
        exit;
    } 
    
    wp_send_json_success(array(
        'message' => '登录成功'
    ));
    exit;
}

add_action('wp_ajax_nopriv_user_signup_action', 'handle_user_signup');
add_action('wp_ajax_user_signup_action', 'handle_user_signup');

function handle_user_signup() {
    // 移除所有默认的新用户注册通知
    remove_action('register_new_user', 'wp_send_new_user_notifications');
    remove_action('edit_user_created_user', 'wp_send_new_user_notifications');
    remove_action('network_site_new_created_user', 'wp_send_new_user_notifications');
    remove_action('network_site_users_created_user', 'wp_send_new_user_notifications');
    remove_action('network_user_new_created_user', 'wp_send_new_user_notifications');
    
    $formData = json_decode(stripslashes($_POST['formData']), true);
    
    $stored_code = get_transient('verification_code_' . $formData['email']);
    if (!$stored_code || $stored_code !== $formData['verificationcode']) {
        wp_send_json_error(array('message' => '验证码错误或已过期'));
        exit;
    }  

    if (!isset($formData['signup_nonce']) || !wp_verify_nonce($formData['signup_nonce'], 'user_signup')) {
        wp_send_json_error(array(
            'message' => '安全验证失败，请刷新页面重试'
        ));
        exit;
    }   
    if (empty($formData['username']) || empty($formData['email']) || empty($formData['password']) || empty($formData['confirmpassword'])) {
        wp_send_json_error(array(
            'message' => '所有字段都为必填项'
        ));
        exit;
    }   
    if ($formData['password'] !== $formData['confirmpassword']) {
        wp_send_json_error(array(
            'message' => '两次输入的密码不一致'
        ));
        exit;
    }   
    if (strlen($formData['password']) < 6) {
        wp_send_json_error(array(
            'message' => '密码长度至少需要6个字符'
        ));
        exit;
    }   
    if (!is_email($formData['email'])) {
        wp_send_json_error(array(
            'message' => '请输入有效的邮箱地址'
        ));
        exit;
    }    
    if (email_exists($formData['email'])) {
        wp_send_json_error(array(
            'message' => '该邮箱已被注册'
        ));
        exit;
    }

    // sanitize_user 默认回调已在主题初始化时一次性替换（允许中文用户名），此处不再临时移除/恢复
    $username = $formData['username'];
    if (!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u', $username)) {
        wp_send_json_error(array(
            'message' => '用户名只能包含中文、字母、数字和下划线'
        ));
        exit;
    }
    if (empty($username) || mb_strlen($username) < 2) {
        wp_send_json_error(array(
            'message' => '用户名长度至少需要2个字符'
        ));
        exit;
    }
    if (username_exists($username)) {
        wp_send_json_error(array(
            'message' => '该用户名已被使用'
        ));
        exit;
    }
    $user_id = wp_create_user(
        $username,
        $formData['password'],
        $formData['email']
    );

    if (is_wp_error($user_id)) {
        $error_code = $user_id->get_error_code();
        $error_message = '';
        
        switch ($error_code) {
            case 'existing_user_login':
                $error_message = '该用户名已被使用';
                break;
            case 'existing_user_email':
                $error_message = '该邮箱已被注册';
                break;
            default:
                $error_message = '注册失败，请稍后重试';
        }
        
        wp_send_json_error(array(
            'message' => $error_message
        ));
        exit;
    }

    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // 通知统一由 fuwari_user_register_notify 事件分发（架构优化：解耦用户模块与消息模块，开关判断集中在消息模块）
    do_action('fuwari_user_register_notify', $user_id);
    delete_transient('verification_code_' . $formData['email']);  
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
    wp_send_json_success(array(
        'message' => '注册成功并已自动登录'
    ));
    exit;
}

function fuwari_allow_chinese_username($username, $raw_username, $strict) {
    if (!$strict) {
        return $username;
    } 
    $username = $raw_username;
    $username = preg_replace('/[^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]]/u', '', $username);
    return $username;
}
// 架构/安全加固：一次性替换核心默认清理回调（允许中文用户名是主题的明确设计）。
// 原 13.12 在 AJAX 注册流程中临时 remove/add_filter，存在竞态且影响面不可控。
// 此处先移除核心默认回调，再挂载主题白名单回调（注册路径另有强正则校验兜底）。
remove_filter('sanitize_user', 'sanitize_user', 10, 3);
add_filter('sanitize_user', 'fuwari_allow_chinese_username', 10, 3);

add_action('wp_ajax_nopriv_send_verification_code', 'handle_send_verification_code');
add_action('wp_ajax_send_verification_code', 'handle_send_verification_code');
function handle_send_verification_code() {
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => '请输入有效的邮箱地址'));
        exit;
    }  
    if (email_exists($email)) {
        wp_send_json_error(array('message' => '该邮箱已被注册'));
        exit;
    }
    $verification_code = sprintf("%06d", mt_rand(0, 999999));
    set_transient('verification_code_' . $email, $verification_code, 5 * MINUTE_IN_SECONDS);
    if (fuwari_verification_code_register_email($email, $verification_code)) {
        wp_send_json_success(array('message' => '验证码已发送'));
    } else {
        wp_send_json_error(array('message' => '验证码发送失败，请稍后重试'));
    }
    exit;
}

add_action('wp_ajax_nopriv_reset_password_action', 'handle_reset_password_request');
add_action('wp_ajax_reset_password_action', 'handle_reset_password_request');

function handle_reset_password_request() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'reset_password_action')) {
        wp_send_json_error(array('message' => '安全验证失败，请刷新页面重试'));
        exit;
    }

    $user_email = sanitize_email($_POST['user_email']);
    
    if (empty($user_email) || !is_email($user_email)) {
        wp_send_json_error(array('message' => '请输入有效的邮箱地址'));
        exit;
    }

    $user = get_user_by('email', $user_email);
    
    if (!$user) {
        wp_send_json_error(array('message' => '该邮箱地址未注册'));
        exit;
    }

    if(fuwari_reset_password_email($user->user_login)){
        wp_send_json_success(array('message' => '重置密码链接已发送到您的邮箱，请查收'));
    }else{
        wp_send_json_error(array('message' => '发送邮件失败，请稍后重试'));
    }
    exit;
}

// 透过代理或者cdn获取访客真实IP
// 安全加固：默认只信任 REMOTE_ADDR（不可伪造）；代理头（X-Forwarded-For / Client-IP）仅在 REMOTE_ADDR
// 无效时作为备选，且逐段校验为合法 IP 地址，避免任意伪造污染登录记录。
function get_client_ip() {
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if ($remote_addr && filter_var($remote_addr, FILTER_VALIDATE_IP)) {
        return $remote_addr;
    }
    $candidates = array();
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $candidates = array_merge($candidates, array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $candidates[] = trim($_SERVER['HTTP_CLIENT_IP']);
    }
    foreach ($candidates as $ip) {
        if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }
    return 'unknown';
}

// 处理用户注册时间
add_action('user_register', 'fuwari_user_register_time');
function fuwari_user_register_time($user_id){
    $user = get_user_by('id', $user_id);
    update_user_meta($user_id, 'register_time', current_time('mysql'));
}

// 处理用户登录时间
add_action('wp_login', 'fuwari_user_login_time');
function fuwari_user_login_time($user_login){
    $user = get_user_by('login', $user_login);
    update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
}

// 处理用户登录IP
add_action('wp_login', 'fuwari_user_login_ip');
function fuwari_user_login_ip($user_login){
    $user = get_user_by('login', $user_login);
    update_user_meta($user->ID, 'last_login_ip', get_client_ip());
}


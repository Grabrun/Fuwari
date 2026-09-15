<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 */
//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){echo'Look your sister';exit;}

// 头像上传处理
add_action('wp_ajax_upload_avatar', 'fuwari_upload_avatar');

function fuwari_upload_avatar() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => '请先登录']);
        return;
    }
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'fuwari_ajax_nonce')) {
        wp_send_json_error(['message' => '非法请求']);
        return;
    }

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(['message' => '文件上传失败']);
        return;
    }

    $file = $_FILES['avatar'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 1 * 1024 * 1024; // 1MB

    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error(['message' => '只支持 JPEG、PNG 和 GIF 格式']);
        return;
    }

    if ($file['size'] > $max_size) {
        wp_send_json_error(['message' => '文件大小不能超过1MB']);
        return;
    }
    $upload_dir = WP_CONTENT_DIR . '/uploads/useravatar';
    if (!file_exists($upload_dir)) {
        wp_mkdir_p($upload_dir);
    }
    $user_id = get_current_user_id();
    // 安全加固：扩展名不再取自用户文件名，改为基于文件真实内容的 MIME 白名单映射（杜绝上传可执行文件）
    $mime_map = array(
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
    );
    $detected_type = '';
    if (function_exists('finfo_open')) {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $detected_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        }
    }
    if (empty($detected_type)) {
        // 降级：读取真实图片信息
        $img_info = @getimagesize($file['tmp_name']);
        $detected_type = isset($img_info['mime']) ? $img_info['mime'] : '';
    }
    if (!isset($mime_map[$detected_type])) {
        wp_send_json_error(['message' => '文件内容不是有效的图片格式']);
        return;
    }
    $extension = $mime_map[$detected_type];
    $random_string = wp_generate_password(8, false);
    $filename = $user_id . '_' . $random_string . '.' . $extension;
    $filepath = $upload_dir . '/' . $filename;
    $old_avatar = get_user_meta($user_id, 'user_avatar', true);
    if ($old_avatar) {
        $old_filepath = WP_CONTENT_DIR . str_replace(content_url(), '', $old_avatar);
        if (file_exists($old_filepath)) {
            unlink($old_filepath);
        }
    }
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $avatar_url = content_url() . '/uploads/useravatar/' . $filename;
        update_user_meta($user_id, 'user_avatar', $avatar_url);

        wp_send_json_success([
            'message' => '头像上传成功',
            'avatar_url' => $avatar_url
        ]);
    } else {
        wp_send_json_error(['message' => '文件上传失败，请重试']);
    }
}

// 用户信息更新处理
add_action('wp_ajax_update_user_profile', 'fuwari_update_user_profile');

function fuwari_update_user_profile() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => '请先登录']);
        return;
    }

    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'fuwari_ajax_nonce')) {
        wp_send_json_error(['message' => '非法请求']);
        return;
    }

    $user_id = get_current_user_id();
    $display_name = sanitize_text_field($_POST['display_name']);
    $user_url = esc_url_raw($_POST['user_url']);
    $description = sanitize_textarea_field($_POST['description']);

    if (empty($display_name)) {
        wp_send_json_error(['message' => '昵称不能为空']);
        return;
    }

    $user_data = array(
        'ID' => $user_id,
        'display_name' => $display_name,
        'user_url' => $user_url,
        'description' => $description
    );

    $result = wp_update_user($user_data);

    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    } else {
        wp_send_json_success(['message' => '个人资料更新成功']);
    }
}

// 用户密码更新处理
add_action('wp_ajax_update_user_password', 'fuwari_update_user_password');

function fuwari_update_user_password() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => '请先登录']);
        return;
    }

    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'fuwari_ajax_nonce')) {
        wp_send_json_error(['message' => '非法请求']);
        return;
    }

    $user_id = get_current_user_id();
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 验证输入不为空
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        wp_send_json_error(['message' => '请填写所有密码字段']);
        return;
    }

    // 验证新密码一致性
    if ($new_password !== $confirm_password) {
        wp_send_json_error(['message' => '新密码与确认密码不一致']);
        return;
    }

    // 验证新密码长度
    if (strlen($new_password) < 6) {
        wp_send_json_error(['message' => '新密码长度至少需要6个字符']);
        return;
    }

    // 验证旧密码是否正确
    $user = get_user_by('id', $user_id);
    if (!wp_check_password($old_password, $user->user_pass, $user_id)) {
        wp_send_json_error(['message' => '旧密码不正确']);
        return;
    }

    // 更新密码
    $result = wp_update_user([
        'ID' => $user_id,
        'user_pass' => $new_password
    ]);

    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    } else {
        wp_send_json_success(['message' => '密码修改成功']);
    }
}

<?php
/**
 * @link https://www.boxmoe.com
 * @package fuwari
 */

//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}
// SMTP 密码加密/解密（安全加固）：密钥由 wp_salt('auth') 派生，不落库；兼容旧版本明文存储
function fuwari_smtp_encrypt($plain) {
    if ('' === (string)$plain || !function_exists('openssl_encrypt')) {
        return $plain;
    }
    $key = hash('sha256', wp_salt('auth'), true);
    $iv = openssl_random_pseudo_bytes(16);
    $cipher = @openssl_encrypt((string)$plain, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    if (false === $cipher) {
        return $plain;
    }
    return 'fuwari_enc:' . base64_encode($iv . $cipher);
}
function fuwari_smtp_decrypt($stored) {
    if (!is_string($stored) || '' === $stored) {
        return $stored;
    }
    // 0.3.0 起前缀为 fuwari_enc:（10 字符）；兼容 0.1.0-0.2.0 的 boxmoe_enc:（11 字符）
    if (0 === strpos($stored, 'fuwari_enc:')) {
        $prefix_len = 10;
    } elseif (0 === strpos($stored, 'boxmoe_enc:')) {
        $prefix_len = 11;
    } else {
        return $stored; // 未加密（旧版本明文或空值），兼容返回
    }
    if (!function_exists('openssl_decrypt')) {
        return '';
    }
    $raw = base64_decode(substr($stored, $prefix_len), true);
    if (false === $raw || strlen($raw) <= 16) {
        return '';
    }
    $key = hash('sha256', wp_salt('auth'), true);
    $iv = substr($raw, 0, 16);
    $cipher = substr($raw, 16);
    $plain = @openssl_decrypt($cipher, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return (false === $plain) ? '' : $plain;
}

if(get_fuwari('fuwari_smtp_mail_switch')){
    // 添加管理菜单
    add_action('admin_menu', 'fuwari_smtp_menu');
    
    // 添加SMTP设置菜单
    function fuwari_smtp_menu() {
        add_menu_page(
            'SMTP设置', 
            'SMTP设置', 
            'manage_options', 
            'fuwari-smtp-settings', 
            'fuwari_smtp_settings_page',
            'dashicons-email',
            100
        );

    }
    
    // SMTP设置页面内容
    function fuwari_smtp_settings_page() {
        if(isset($_POST['fuwari_smtp_save'])) {
            // 安全加固：校验 nonce，防 CSRF
            check_admin_referer('fuwari_smtp_settings_action');
            update_option('fuwari_smtp_host', sanitize_text_field($_POST['smtp_host']));
            update_option('fuwari_smtp_port', sanitize_text_field($_POST['smtp_port']));
            update_option('fuwari_smtp_user', sanitize_text_field($_POST['smtp_user']));
            // 安全加固：密码留空表示保持原密码（不回显、不覆盖）；非空时加密后存储
            if (isset($_POST['smtp_pass']) && '' !== (string)$_POST['smtp_pass']) {
                update_option('fuwari_smtp_pass', fuwari_smtp_encrypt(sanitize_text_field($_POST['smtp_pass'])));
            }
            update_option('fuwari_smtp_from', sanitize_text_field($_POST['smtp_from']));
            update_option('fuwari_smtp_name', sanitize_text_field($_POST['smtp_name']));
            echo '<div class="updated"><p>设置已保存！</p></div>';
        }

        // 添加测试邮件发送功能
        if(isset($_POST['fuwari_smtp_test'])) {
            // 安全加固：校验 nonce，防 CSRF
            check_admin_referer('fuwari_smtp_settings_action');
            $to = sanitize_email($_POST['test_email']);
            $subject = '测试邮件 - ' . get_bloginfo('name');
            $message = '这是一封测试邮件，如果您收到这封邮件，说明SMTP配置正确。';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            $result = wp_mail($to, $subject, $message, $headers);
            
            if($result) {
                echo '<div class="updated"><p>测试邮件发送成功！请检查收件箱。</p></div>';
            } else {
                echo '<div class="error"><p>测试邮件发送失败，请检查SMTP配置。</p></div>';
            }
        }
        ?>
        <div class="wrap">
            <h2>SMTP邮件设置</h2>
            <form method="post">
                <?php wp_nonce_field('fuwari_smtp_settings_action'); ?>
                <table class="form-table">
                    <tr>
                        <th>SMTP服务器</th>
                        <td><input type="text" name="smtp_host" value="<?php echo esc_attr(get_option('fuwari_smtp_host')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>SMTP端口</th>
                        <td><input type="text" name="smtp_port" value="<?php echo esc_attr(get_option('fuwari_smtp_port')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>邮箱账号</th>
                        <td><input type="text" name="smtp_user" value="<?php echo esc_attr(get_option('fuwari_smtp_user')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>邮箱密码</th>
                        <td><input type="password" name="smtp_pass" value="" class="regular-text" autocomplete="new-password" placeholder="<?php echo (get_option('fuwari_smtp_pass') ? '已设置（留空保持不变，不再回显）' : '未设置'); ?>"></td>
                    </tr>
                    <tr>
                        <th>发件人邮箱</th>
                        <td><input type="text" name="smtp_from" value="<?php echo esc_attr(get_option('fuwari_smtp_from')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>发件人名称</th>
                        <td><input type="text" name="smtp_name" value="<?php echo esc_attr(get_option('fuwari_smtp_name')); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="fuwari_smtp_save" class="button-primary" value="保存设置">
                </p>
            </form>

            <!-- 添加测试邮件表单 -->
            <h3>测试邮件发送</h3>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th>测试收件邮箱</th>
                        <td>
                            <input type="email" name="test_email" class="regular-text" required>
                            <p class="description">请输入用于测试的收件邮箱地址</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="fuwari_smtp_test" class="button-secondary" value="发送测试邮件">
                </p>
            </form>
        </div>
        <?php
    }
    
    // 配置WordPress邮件发送
    add_action('phpmailer_init', 'fuwari_smtp_config');
    function fuwari_smtp_config($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host = get_option('fuwari_smtp_host');
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = get_option('fuwari_smtp_port');
        $phpmailer->Username = get_option('fuwari_smtp_user');
        $phpmailer->Password = fuwari_smtp_decrypt(get_option('fuwari_smtp_pass'));
        $phpmailer->From = get_option('fuwari_smtp_from');
        $phpmailer->FromName = get_option('fuwari_smtp_name');
        $phpmailer->SMTPSecure = 'ssl';
    }
}

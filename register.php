<?php

declare(strict_types=1);

/**
 * 用户注册页面
 */

require_once __DIR__ . '/includes/init.php';

// 已登录用户跳转到个人中心
require_guest();

$errors = [];
$old_input = [];

if (is_post()) {
    // 1. 验证 CSRF
    if (!csrf_verify((string) get_post(CSRF_TOKEN_NAME, ''))) {
        $errors['csrf'] = '安全验证失败，请重试';
    } else {
        // 2. 清洗输入
        $username = sanitize_string((string) get_post('username', ''));
        $email = sanitize_email((string) get_post('email', ''));
        $password_encrypted = (string) get_post('password', '');
        $password_confirm_encrypted = (string) get_post('password_confirm', '');

        // 解密密码
        $password = crypto_decrypt_password($password_encrypted);
        $password_confirm = crypto_decrypt_password($password_confirm_encrypted);

        $old_input = compact('username', 'email');

        // 3. 验证表单
        $validation = validate_register_form([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirm' => $password_confirm,
        ]);

        if (!$validation['valid']) {
            $errors = $validation['errors'];
        } else {
            // 4. 注册用户
            $result = register_user($username, $email, $password);

            if ($result['success']) {
                set_flash('success', '注册成功，请登录');
                csrf_refresh();
                redirect('login.php');
            } else {
                $errors['register'] = $result['message'];
            }
        }
    }
}

$page_title = '用户注册';
require_once TEMPLATES_PATH . '/header.php';
require_once TEMPLATES_PATH . '/alerts.php';
require_once TEMPLATES_PATH . '/forms/register_form.php';
require_once TEMPLATES_PATH . '/footer.php';

<?php

declare(strict_types=1);

/**
 * 用户登录页面
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
        $username_or_email = sanitize_string((string) get_post('username', ''));
        $password_encrypted = (string) get_post('password', '');
        $remember = get_post('remember') === '1';

        // 解密密码
        $password = crypto_decrypt_password($password_encrypted);

        $old_input = ['username' => $username_or_email];

        // 3. 验证表单
        $validation = validate_login_form([
            'username' => $username_or_email,
            'password' => $password,
        ]);

        if (!$validation['valid']) {
            $errors = $validation['errors'];
        } else {
            // 4. 登录验证
            $result = login_user($username_or_email, $password);

            if ($result['success']) {
                set_user_logged_in((int) $result['user']['id'], $remember);
                set_flash('success', '登录成功，欢迎回来！');
                csrf_refresh();
                redirect('profile.php');
            } else {
                $errors['login'] = $result['message'];
            }
        }
    }
}

$page_title = '用户登录';
require_once TEMPLATES_PATH . '/header.php';
require_once TEMPLATES_PATH . '/alerts.php';
require_once TEMPLATES_PATH . '/forms/login_form.php';
require_once TEMPLATES_PATH . '/footer.php';

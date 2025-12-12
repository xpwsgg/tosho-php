<?php

declare(strict_types=1);

/**
 * 首页
 * 根据登录状态跳转到相应页面
 */

require_once __DIR__ . '/includes/init.php';

if (is_logged_in()) {
    redirect('profile.php');
} else {
    redirect('login.php');
}

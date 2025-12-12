<?php

declare(strict_types=1);

/**
 * 退出登录
 */

require_once __DIR__ . '/includes/init.php';

// 清除登录状态
clear_user_session();

// 重新启动 session 以便设置 flash 消息
session_start();
set_flash('info', '已退出登录');

redirect('login.php');

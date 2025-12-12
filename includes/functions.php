<?php

declare(strict_types=1);

/**
 * 公共辅助函数
 * 重定向、Flash消息、请求处理等
 */

/**
 * 页面重定向
 */
function redirect(string $url): never
{
    header("Location: {$url}");
    exit;
}

/**
 * 设置 Flash 消息（一次性提示）
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

/**
 * 获取并清除 Flash 消息
 * @return array<string, string>
 */
function get_flash(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * 要求已登录（否则跳转登录页）
 */
function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('warning', '请先登录');
        redirect('login.php');
    }
}

/**
 * 要求未登录（已登录跳转个人中心）
 */
function require_guest(): void
{
    if (is_logged_in()) {
        redirect('profile.php');
    }
}

/**
 * 获取 POST 数据
 */
function get_post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

/**
 * 检查是否 POST 请求
 */
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * 获取 GET 数据
 */
function get_query(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}

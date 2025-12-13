<?php

declare(strict_types=1);

/**
 * Session 管理
 * 登录状态、记住我功能
 */

/**
 * 初始化 session 配置
 */
function session_init(array $config): void
{
    if (session_status() === PHP_SESSION_NONE) {
        // 安全的 session 配置
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');

        session_name($config['name']);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();

        // 检查"记住我"cookie
        handle_remember_me();
    }
}

/**
 * 检查用户是否已登录
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

/**
 * 获取当前登录用户 ID
 */
function get_current_user_id(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * 获取当前登录用户信息
 */
function get_logged_in_user(): ?array
{
    $user_id = get_current_user_id();
    if ($user_id === null) {
        return null;
    }
    return get_user_by_id($user_id);
}

/**
 * 设置登录状态
 */
function set_user_logged_in(int $user_id, bool $remember = false): void
{
    global $config;

    // 重新生成 session ID 防止会话固定攻击
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['login_time'] = time();

    if ($remember) {
        set_remember_cookie($user_id, $config['session']['remember_lifetime']);
    }
}

/**
 * 清除登录状态
 */
function clear_user_session(): void
{
    $_SESSION = [];

    // 删除 session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // 删除"记住我"cookie
    clear_remember_cookie();

    session_destroy();
}

/**
 * 设置"记住我"cookie
 */
function set_remember_cookie(int $user_id, int $lifetime): void
{
    // 生成安全 token
    $token = bin2hex(random_bytes(32));
    $expiry = time() + $lifetime;

    // 简化版：直接存储用户ID和token
    // 生产环境应存到数据库的 remember_tokens 表
    $cookie_value = base64_encode($user_id . '|' . $token);

    setcookie(
        'remember_token',
        $cookie_value,
        [
            'expires' => $expiry,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict',
        ]
    );
}

/**
 * 处理"记住我"功能
 */
function handle_remember_me(): void
{
    if (is_logged_in()) {
        return;
    }

    if (!isset($_COOKIE['remember_token'])) {
        return;
    }

    // 解析 cookie
    $decoded = base64_decode($_COOKIE['remember_token'], true);
    if ($decoded === false) {
        clear_remember_cookie();
        return;
    }

    $parts = explode('|', $decoded);
    if (count($parts) !== 2) {
        clear_remember_cookie();
        return;
    }

    [$user_id, $token] = $parts;
    $user_id = (int) $user_id;

    // 验证用户存在
    $user = get_user_by_id($user_id);
    if ($user === null) {
        clear_remember_cookie();
        return;
    }

    // 简化版：直接登录
    // 生产环境应验证数据库中的 token hash
    $_SESSION['user_id'] = $user_id;
    session_regenerate_id(true);
}

/**
 * 清除"记住我"cookie
 */
function clear_remember_cookie(): void
{
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 42000, '/');
    }
}

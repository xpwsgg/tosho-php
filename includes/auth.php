<?php

declare(strict_types=1);

/**
 * 认证相关函数
 * 用户注册、登录、查询等
 */

/**
 * 注册用户
 * @return array{success: bool, message: string, user_id: ?int}
 */
function register_user(string $username, string $email, string $password): array
{
    try {
        // 检查用户名是否存在
        if (username_exists($username)) {
            return ['success' => false, 'message' => '用户名已存在', 'user_id' => null];
        }

        // 检查邮箱是否存在
        if (email_exists($email)) {
            return ['success' => false, 'message' => '邮箱已被注册', 'user_id' => null];
        }

        // 密码哈希
        $password_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // 插入用户
        $user_id = db_insert('users', [
            'username' => $username,
            'email' => $email,
            'password' => $password_hash,
        ]);

        return ['success' => true, 'message' => '注册成功', 'user_id' => $user_id];

    } catch (Throwable $e) {
        return ['success' => false, 'message' => '注册失败，请稍后重试', 'user_id' => null];
    }
}

/**
 * 登录验证
 * @return array{success: bool, message: string, user: ?array}
 */
function login_user(string $username_or_email, string $password): array
{
    try {
        // 支持用户名或邮箱登录
        $user = str_contains($username_or_email, '@')
            ? get_user_by_email($username_or_email)
            : get_user_by_username($username_or_email);

        if ($user === null) {
            return ['success' => false, 'message' => '用户名或密码错误', 'user' => null];
        }

        // 验证密码
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => '用户名或密码错误', 'user' => null];
        }

        // 检查是否需要重新哈希（算法升级时）
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT, ['cost' => 12])) {
            $new_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            db_update('users', ['password' => $new_hash], 'id = ?', [$user['id']]);
        }

        // 移除密码字段后返回
        unset($user['password']);

        return ['success' => true, 'message' => '登录成功', 'user' => $user];

    } catch (Throwable $e) {
        return ['success' => false, 'message' => '登录失败，请稍后重试', 'user' => null];
    }
}

/**
 * 通过 ID 获取用户
 */
function get_user_by_id(int $id): ?array
{
    $user = db_fetch_one('SELECT * FROM users WHERE id = ?', [$id]);
    if ($user !== null) {
        unset($user['password']);
    }
    return $user;
}

/**
 * 通过用户名获取用户（包含密码，用于验证）
 */
function get_user_by_username(string $username): ?array
{
    return db_fetch_one('SELECT * FROM users WHERE username = ?', [$username]);
}

/**
 * 通过邮箱获取用户（包含密码，用于验证）
 */
function get_user_by_email(string $email): ?array
{
    return db_fetch_one('SELECT * FROM users WHERE email = ?', [$email]);
}

/**
 * 检查用户名是否存在
 */
function username_exists(string $username): bool
{
    return get_user_by_username($username) !== null;
}

/**
 * 检查邮箱是否存在
 */
function email_exists(string $email): bool
{
    return get_user_by_email($email) !== null;
}

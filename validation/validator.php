<?php

declare(strict_types=1);

/**
 * 输入验证函数
 * 验证用户输入的格式和规则
 */

/**
 * 验证非空
 */
function validate_required(mixed $value, string $field): ?string
{
    if ($value === null || $value === '') {
        return "{$field}不能为空";
    }
    return null;
}

/**
 * 验证邮箱格式
 */
function validate_email(string $email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '请输入有效的邮箱地址';
    }
    return null;
}

/**
 * 验证用户名格式
 */
function validate_username(string $username): ?string
{
    $length = mb_strlen($username);

    if ($length < USERNAME_MIN_LENGTH) {
        return '用户名至少' . USERNAME_MIN_LENGTH . '个字符';
    }

    if ($length > USERNAME_MAX_LENGTH) {
        return '用户名最多' . USERNAME_MAX_LENGTH . '个字符';
    }

    // 允许字母、数字、下划线和中文
    if (!preg_match('/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]+$/u', $username)) {
        return '用户名只能包含字母、数字、下划线和中文';
    }

    return null;
}

/**
 * 验证密码强度
 */
function validate_password(string $password): ?string
{
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return '密码至少' . PASSWORD_MIN_LENGTH . '个字符';
    }
    return null;
}

/**
 * 验证确认密码
 */
function validate_password_confirm(string $password, string $confirm): ?string
{
    if ($password !== $confirm) {
        return '两次输入的密码不一致';
    }
    return null;
}

/**
 * 批量验证注册表单
 * @return array{valid: bool, errors: array<string, string>}
 */
function validate_register_form(array $data): array
{
    $errors = [];

    // 用户名验证
    if ($error = validate_required($data['username'] ?? '', '用户名')) {
        $errors['username'] = $error;
    } elseif ($error = validate_username($data['username'])) {
        $errors['username'] = $error;
    }

    // 邮箱验证
    if ($error = validate_required($data['email'] ?? '', '邮箱')) {
        $errors['email'] = $error;
    } elseif ($error = validate_email($data['email'])) {
        $errors['email'] = $error;
    }

    // 密码验证
    if ($error = validate_required($data['password'] ?? '', '密码')) {
        $errors['password'] = $error;
    } elseif ($error = validate_password($data['password'])) {
        $errors['password'] = $error;
    }

    // 确认密码验证
    if ($error = validate_password_confirm($data['password'] ?? '', $data['password_confirm'] ?? '')) {
        $errors['password_confirm'] = $error;
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors,
    ];
}

/**
 * 批量验证登录表单
 * @return array{valid: bool, errors: array<string, string>}
 */
function validate_login_form(array $data): array
{
    $errors = [];

    if ($error = validate_required($data['username'] ?? '', '用户名/邮箱')) {
        $errors['username'] = $error;
    }

    if ($error = validate_required($data['password'] ?? '', '密码')) {
        $errors['password'] = $error;
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors,
    ];
}

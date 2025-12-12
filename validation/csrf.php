<?php

declare(strict_types=1);

/**
 * CSRF 防护机制
 * Token 生成、验证和刷新
 */

/**
 * 生成 CSRF token
 */
function csrf_generate(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * 获取 CSRF 隐藏表单字段 HTML
 */
function csrf_field(): string
{
    $token = csrf_generate();
    return sprintf(
        '<input type="hidden" name="%s" value="%s">',
        CSRF_TOKEN_NAME,
        htmlspecialchars($token, ENT_QUOTES | ENT_HTML5, 'UTF-8')
    );
}

/**
 * 验证 CSRF token
 */
function csrf_verify(string $token): bool
{
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
        return false;
    }
    // 使用 hash_equals 防止时序攻击
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * 刷新 CSRF token（表单提交成功后调用）
 */
function csrf_refresh(): void
{
    unset($_SESSION[CSRF_TOKEN_NAME]);
}

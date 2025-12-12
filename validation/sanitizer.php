<?php

declare(strict_types=1);

/**
 * 输入清洗函数
 * 过滤和清洗用户输入，XSS防护
 */

/**
 * 清洗字符串输入（去除首尾空白）
 */
function sanitize_string(string $input): string
{
    return trim($input);
}

/**
 * 清洗邮箱地址
 */
function sanitize_email(string $email): string
{
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return $email !== false ? $email : '';
}

/**
 * 输出转义（XSS防护）
 */
function escape_html(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * 转义数组中的所有字符串值
 */
function escape_array(array $data): array
{
    $escaped = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $escaped[$key] = escape_html($value);
        } elseif (is_array($value)) {
            $escaped[$key] = escape_array($value);
        } else {
            $escaped[$key] = $value;
        }
    }
    return $escaped;
}

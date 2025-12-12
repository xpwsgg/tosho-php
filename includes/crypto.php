<?php

declare(strict_types=1);

/**
 * RSA 加解密模块
 * 用于前端密码加密传输
 */

/**
 * 获取密钥存储目录
 */
function crypto_get_keys_dir(): string
{
    return ROOT_PATH . '/keys';
}

/**
 * 获取私钥文件路径
 */
function crypto_get_private_key_path(): string
{
    return crypto_get_keys_dir() . '/private.pem';
}

/**
 * 获取公钥文件路径
 */
function crypto_get_public_key_path(): string
{
    return crypto_get_keys_dir() . '/public.pem';
}

/**
 * 初始化 RSA 密钥对（如果不存在则生成）
 */
function crypto_init_keys(): void
{
    $keysDir = crypto_get_keys_dir();
    $privateKeyPath = crypto_get_private_key_path();
    $publicKeyPath = crypto_get_public_key_path();

    // 如果密钥已存在，跳过生成
    if (file_exists($privateKeyPath) && file_exists($publicKeyPath)) {
        return;
    }

    // 创建密钥目录
    if (!is_dir($keysDir)) {
        mkdir($keysDir, 0700, true);
    }

    // 生成 RSA 密钥对
    $config = [
        'digest_alg' => 'sha256',
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ];

    $keyPair = openssl_pkey_new($config);
    if ($keyPair === false) {
        throw new RuntimeException('RSA 密钥生成失败: ' . openssl_error_string());
    }

    // 导出私钥
    openssl_pkey_export($keyPair, $privateKey);
    file_put_contents($privateKeyPath, $privateKey);
    chmod($privateKeyPath, 0600);

    // 导出公钥
    $keyDetails = openssl_pkey_get_details($keyPair);
    file_put_contents($publicKeyPath, $keyDetails['key']);
    chmod($publicKeyPath, 0644);

    // 创建 .htaccess 保护密钥目录（Apache）
    $htaccess = $keysDir . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Deny from all\n");
    }
}

/**
 * 获取公钥内容（用于前端加密）
 */
function crypto_get_public_key(): string
{
    crypto_init_keys();

    $publicKey = file_get_contents(crypto_get_public_key_path());
    if ($publicKey === false) {
        throw new RuntimeException('无法读取公钥文件');
    }

    return $publicKey;
}

/**
 * RSA 私钥解密
 * @param string $encryptedData Base64 编码的加密数据
 * @return string|null 解密后的明文，失败返回 null
 */
function crypto_decrypt(string $encryptedData): ?string
{
    $privateKeyPath = crypto_get_private_key_path();

    if (!file_exists($privateKeyPath)) {
        return null;
    }

    $privateKey = file_get_contents($privateKeyPath);
    if ($privateKey === false) {
        return null;
    }

    // Base64 解码
    $encrypted = base64_decode($encryptedData, true);
    if ($encrypted === false) {
        return null;
    }

    // RSA 解密
    $decrypted = '';
    $result = openssl_private_decrypt($encrypted, $decrypted, $privateKey, OPENSSL_PKCS1_PADDING);

    if ($result === false) {
        return null;
    }

    return $decrypted;
}

/**
 * 解密密码字段
 * 如果解密失败，返回原始值（兼容未加密的情况）
 */
function crypto_decrypt_password(string $password): string
{
    // 尝试解密
    $decrypted = crypto_decrypt($password);

    // 解密成功返回解密后的值，否则返回原值
    return $decrypted ?? $password;
}

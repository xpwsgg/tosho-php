<?php

declare(strict_types=1);

/**
 * 常量定义
 * 路径、安全配置等全局常量
 */

// 路径常量
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DB_PATH', ROOT_PATH . '/db');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('TEMPLATES_PATH', ROOT_PATH . '/templates');
define('VALIDATION_PATH', ROOT_PATH . '/validation');

// 安全配置
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);
define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 32);

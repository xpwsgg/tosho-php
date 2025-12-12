<?php

declare(strict_types=1);

/**
 * 应用初始化
 * 加载配置、启动 session、初始化数据库
 */

// 1. 加载常量定义
require_once __DIR__ . '/../config/constants.php';

// 2. 加载配置（全局变量，供数据库驱动等模块使用）
$config = require CONFIG_PATH . '/config.php';

// 3. 设置时区和错误报告
date_default_timezone_set($config['timezone']);

if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// 4. 加载核心文件
require_once VALIDATION_PATH . '/sanitizer.php';
require_once VALIDATION_PATH . '/validator.php';
require_once VALIDATION_PATH . '/csrf.php';
require_once DB_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';
require_once INCLUDES_PATH . '/session.php';
require_once INCLUDES_PATH . '/crypto.php';
require_once INCLUDES_PATH . '/auth.php';

// 5. 初始化 session
session_init($config['session']);

// 6. 初始化数据库
db_init();

<?php

declare(strict_types=1);

/**
 * 应用配置
 * 站点名称、调试模式、session配置等
 */

return [
    // 站点名称
    'site_name' => 'Tosho用户系统',

    // 调试模式（生产环境设为 false）
    'debug' => true,

    // 时区
    'timezone' => 'Asia/Tokyo',

    // 数据库配置
    'database' => [
        // 驱动类型：sqlite 或 mysql
        'driver' => 'sqlite',

        // SQLite 配置
        'sqlite' => [
            'path' => dirname(__DIR__) . '/db/tosho.sqlite',
        ],

        // MySQL 配置
        'mysql' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'tosho',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ],
    ],

    // Session 配置
    'session' => [
        'name' => 'TOSHO_SESSION',
        'lifetime' => 3600,             // 普通登录：1小时
        'remember_lifetime' => 604800,  // 记住我：7天
    ],
];

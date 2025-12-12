<?php

declare(strict_types=1);

/**
 * 数据库驱动接口
 * 定义所有数据库驱动必须实现的方法
 */
interface DatabaseDriver
{
    /**
     * 获取 PDO 连接实例
     */
    public function getConnection(): PDO;

    /**
     * 初始化数据库表结构
     */
    public function initTables(): void;

    /**
     * 获取自增主键的 SQL 语法
     */
    public function getAutoIncrementSyntax(): string;

    /**
     * 获取当前时间戳的 SQL 语法
     */
    public function getCurrentTimestampSyntax(): string;
}

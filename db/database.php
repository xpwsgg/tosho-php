<?php

declare(strict_types=1);

/**
 * 数据库管理器
 * 根据配置加载对应的数据库驱动，提供统一的数据库操作接口
 */

// 加载驱动接口
require_once __DIR__ . '/DatabaseDriver.php';

/**
 * 获取数据库驱动实例（单例模式）
 */
function db_driver(): DatabaseDriver
{
    static $driver = null;

    if ($driver === null) {
        global $config;

        $dbConfig = $config['database'];
        $driverName = $dbConfig['driver'];

        // 加载对应的驱动文件
        $driverFile = __DIR__ . '/drivers/' . ucfirst($driverName) . 'Driver.php';

        if (!file_exists($driverFile)) {
            throw new RuntimeException("不支持的数据库驱动: {$driverName}");
        }

        require_once $driverFile;

        // 实例化驱动
        $driverClass = ucfirst($driverName) . 'Driver';
        $driver = new $driverClass($dbConfig[$driverName]);
    }

    return $driver;
}

/**
 * 获取数据库连接
 */
function db_connect(): PDO
{
    return db_driver()->getConnection();
}

/**
 * 初始化数据库表
 */
function db_init(): void
{
    db_driver()->initTables();
}

/**
 * 执行预处理查询
 */
function db_query(string $sql, array $params = []): PDOStatement
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * 获取单行记录
 */
function db_fetch_one(string $sql, array $params = []): ?array
{
    $stmt = db_query($sql, $params);
    $result = $stmt->fetch();
    return $result !== false ? $result : null;
}

/**
 * 获取多行记录
 */
function db_fetch_all(string $sql, array $params = []): array
{
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * 插入记录并返回 ID
 */
function db_insert(string $table, array $data): int
{
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    db_query($sql, array_values($data));

    return (int) db_connect()->lastInsertId();
}

/**
 * 更新记录
 */
function db_update(string $table, array $data, string $where, array $whereParams = []): int
{
    $setParts = array_map(fn($col) => "{$col} = ?", array_keys($data));
    $setClause = implode(', ', $setParts);

    $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
    $stmt = db_query($sql, [...array_values($data), ...$whereParams]);

    return $stmt->rowCount();
}

/**
 * 删除记录
 */
function db_delete(string $table, string $where, array $whereParams = []): int
{
    $sql = "DELETE FROM {$table} WHERE {$where}";
    $stmt = db_query($sql, $whereParams);

    return $stmt->rowCount();
}

/**
 * 获取当前数据库驱动名称
 */
function db_driver_name(): string
{
    global $config;
    return $config['database']['driver'];
}

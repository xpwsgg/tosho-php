<?php

declare(strict_types=1);

/**
 * MySQL 数据库驱动
 */
class MysqlDriver implements DatabaseDriver
{
    private ?PDO $pdo = null;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取 PDO 连接实例（单例）
     */
    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['database'],
                    $this->config['charset']
                );

                $this->pdo = new PDO(
                    $dsn,
                    $this->config['username'],
                    $this->config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']}",
                    ]
                );
            } catch (PDOException $e) {
                throw new RuntimeException('MySQL 数据库连接失败: ' . $e->getMessage());
            }
        }

        return $this->pdo;
    }

    /**
     * 初始化数据库表结构
     */
    public function initTables(): void
    {
        $pdo = $this->getConnection();

        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(32) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_users_username (username),
                INDEX idx_users_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        $pdo->exec($sql);
    }

    /**
     * 获取自增主键的 SQL 语法
     */
    public function getAutoIncrementSyntax(): string
    {
        return 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
    }

    /**
     * 获取当前时间戳的 SQL 语法
     */
    public function getCurrentTimestampSyntax(): string
    {
        return 'CURRENT_TIMESTAMP';
    }
}

<?php

declare(strict_types=1);

/**
 * SQLite 数据库驱动
 */
class SqliteDriver implements DatabaseDriver
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
                $this->pdo = new PDO(
                    'sqlite:' . $this->config['path'],
                    null,
                    null,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                // SQLite 性能优化
                $this->pdo->exec('PRAGMA journal_mode = WAL');
                $this->pdo->exec('PRAGMA foreign_keys = ON');
            } catch (PDOException $e) {
                throw new RuntimeException('SQLite 数据库连接失败: ' . $e->getMessage());
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
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(32) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
            CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
        SQL;

        $pdo->exec($sql);
    }

    /**
     * 获取自增主键的 SQL 语法
     */
    public function getAutoIncrementSyntax(): string
    {
        return 'INTEGER PRIMARY KEY AUTOINCREMENT';
    }

    /**
     * 获取当前时间戳的 SQL 语法
     */
    public function getCurrentTimestampSyntax(): string
    {
        return 'CURRENT_TIMESTAMP';
    }
}

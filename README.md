# Tosho PHP 用户系统

基于 PHP 8.1+ 的极简用户注册登录系统，采用纯手写面向过程编程实现，不使用任何第三方框架。

## 项目架构

```
tosho-php/
├── config/                     # 配置层
│   ├── constants.php           # 路径和安全常量
│   └── config.php              # 应用配置（站点、时区、数据库、Session）
│
├── db/                         # 数据层
│   ├── DatabaseDriver.php      # 数据库驱动接口
│   ├── database.php            # 数据库管理器（统一 API）
│   ├── drivers/
│   │   ├── SqliteDriver.php    # SQLite 驱动
│   │   └── MysqlDriver.php     # MySQL 驱动
│   └── tosho.sqlite            # SQLite 数据库文件（运行时生成）
│
├── validation/                 # 验证层
│   ├── sanitizer.php           # 输入清洗、XSS 防护
│   ├── validator.php           # 表单验证规则
│   └── csrf.php                # CSRF Token 机制
│
├── includes/                   # 核心逻辑层
│   ├── init.php                # 应用初始化入口
│   ├── functions.php           # 公共辅助函数
│   ├── session.php             # Session 管理、记住我功能
│   └── auth.php                # 用户认证（注册/登录）
│
├── templates/                  # 视图层
│   ├── header.php              # 页头 + 导航栏
│   ├── footer.php              # 页脚
│   ├── alerts.php              # Flash 消息提示
│   └── forms/
│       ├── register_form.php   # 注册表单
│       └── login_form.php      # 登录表单
│
├── index.php                   # 首页（路由分发）
├── register.php                # 注册页面
├── login.php                   # 登录页面
├── profile.php                 # 个人中心
└── logout.php                  # 退出登录
```

## 开发思路

### 设计原则

1. **极简主义** - 不使用框架，纯 PHP 实现，便于学习和理解
2. **面向过程** - 使用函数式编程风格，避免过度设计
3. **安全优先** - 内置 SQL 注入、XSS、CSRF 防护
4. **可扩展性** - 数据库驱动可切换，配置与代码分离

### 分层架构

```
┌─────────────────────────────────────────────────────────┐
│                    页面入口层                            │
│         index.php / register.php / login.php            │
├─────────────────────────────────────────────────────────┤
│                    视图层 (templates/)                   │
│              header / footer / forms / alerts           │
├─────────────────────────────────────────────────────────┤
│                    业务逻辑层 (includes/)                │
│              auth.php / session.php / functions.php     │
├─────────────────────────────────────────────────────────┤
│                    验证层 (validation/)                  │
│           validator.php / sanitizer.php / csrf.php      │
├─────────────────────────────────────────────────────────┤
│                    数据访问层 (db/)                      │
│         database.php → SqliteDriver / MysqlDriver       │
├─────────────────────────────────────────────────────────┤
│                    配置层 (config/)                      │
│              config.php / constants.php                 │
└─────────────────────────────────────────────────────────┘
```



## 运行方式

```bash
cd /Users/xiao/Documents/code/tosho-php
php -S localhost:8000
```

访问 http://localhost:8000

### 安全特性

| 防护类型 | 实现方式 |
|---------|---------|
| SQL 注入 | PDO 预处理语句 |
| XSS 攻击 | `htmlspecialchars()` 输出转义 |
| CSRF 攻击 | Token 验证机制 |
| 密码安全 | `password_hash()` + `password_verify()` |
| 会话安全 | HttpOnly, Secure, SameSite=Strict |
| 会话固定 | 登录时 `session_regenerate_id()` |

## 数据库配置

### 切换数据库驱动

编辑 `config/config.php`：

```php
'database' => [
    // 切换驱动：'sqlite' 或 'mysql'
    'driver' => 'sqlite',

    'sqlite' => [
        'path' => dirname(__DIR__) . '/db/tosho.sqlite',
    ],

    'mysql' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'tosho',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
],
```

### 数据表结构

**users 表：**

| 字段 | 类型 | 说明 |
|-----|------|-----|
| id | INTEGER/INT | 主键，自增 |
| username | VARCHAR(32) | 用户名，唯一 |
| email | VARCHAR(255) | 邮箱，唯一 |
| password | VARCHAR(255) | 密码哈希 |
| created_at | DATETIME | 创建时间 |

## 技术栈

- PHP 8.1+（严格类型声明）
- SQLite / MySQL 数据库
- Bootstrap 5 响应式前端
- 原生 Session 会话管理

## 代码规范

- 所有文件使用 `declare(strict_types=1)`
- 使用 `require_once` 确保文件唯一引入
- 错误处理使用 `try-catch` 结构
- 面向过程编程，函数式封装

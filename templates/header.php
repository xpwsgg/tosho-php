<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape_html($page_title ?? '用户系统') ?> - <?= escape_html($config['site_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsencrypt@3.3.2/bin/jsencrypt.min.js"></script>
    <script>
        // RSA 公钥（用于密码加密）
        const RSA_PUBLIC_KEY = <?= json_encode(crypto_get_public_key(), JSON_UNESCAPED_UNICODE) ?>;

        /**
         * RSA 加密函数
         */
        function encryptPassword(password) {
            const encrypt = new JSEncrypt();
            encrypt.setPublicKey(RSA_PUBLIC_KEY);
            return encrypt.encrypt(password);
        }
    </script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?= escape_html($config['site_name']) ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">个人中心</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">退出</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">登录</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">注册</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container py-4">

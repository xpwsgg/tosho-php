<?php declare(strict_types=1); ?>
<div class="form-container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">用户登录</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= escape_html($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" id="loginForm" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="username" class="form-label">用户名 / 邮箱</label>
                    <input type="text"
                           class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                           id="username"
                           name="username"
                           value="<?= escape_html($old_input['username'] ?? '') ?>"
                           required
                           placeholder="请输入用户名或邮箱">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">密码</label>
                    <input type="password"
                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                           id="password"
                           name="password"
                           required
                           placeholder="请输入密码">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           id="remember"
                           name="remember"
                           value="1">
                    <label class="form-check-label" for="remember">记住我（7天内免登录）</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">登录</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center">
            没有账号？<a href="register.php">立即注册</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const passwordField = document.getElementById('password');
    const password = passwordField.value;

    if (password) {
        // 加密密码
        const encrypted = encryptPassword(password);
        if (encrypted) {
            passwordField.value = encrypted;
        }
    }
});
</script>

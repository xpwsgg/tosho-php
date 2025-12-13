<?php declare(strict_types=1); ?>
<div class="form-container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">用户注册</h4>
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

            <form method="POST" action="register.php" id="registerForm" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="username" class="form-label">用户名</label>
                    <input type="text"
                           class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                           id="username"
                           name="username"
                           value="<?= escape_html($old_input['username'] ?? '') ?>"
                           required
                           minlength="<?= USERNAME_MIN_LENGTH ?>"
                           maxlength="<?= USERNAME_MAX_LENGTH ?>"
                           placeholder="请输入用户名">
                    <div class="form-text">3-32个字符，支持字母、数字、下划线和中文</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">邮箱地址</label>
                    <input type="email"
                           class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                           id="email"
                           name="email"
                           value="<?= escape_html($old_input['email'] ?? '') ?>"
                           required
                           placeholder="请输入邮箱地址">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">密码</label>
                    <input type="password"
                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                           id="password"
                           name="password"
                           required
                           minlength="<?= PASSWORD_MIN_LENGTH ?>"
                           placeholder="请输入密码">
                    <div class="form-text">至少<?= PASSWORD_MIN_LENGTH ?>个字符</div>
                </div>

                <div class="mb-3">
                    <label for="password_confirm" class="form-label">确认密码</label>
                    <input type="password"
                           class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                           id="password_confirm"
                           name="password_confirm"
                           required
                           placeholder="请再次输入密码">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">注册</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center">
            已有账号？<a href="login.php">立即登录</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirm');
    const password = passwordField.value;
    const confirm = confirmField.value;

    // 前端验证两次密码是否一致
    if (password !== confirm) {
        e.preventDefault();
        alert('两次输入的密码不一致');
        return;
    }

    if (password) {
        // 加密密码
        const encryptedPassword = encryptPassword(password);
        const encryptedConfirm = encryptPassword(confirm);

        if (encryptedPassword && encryptedConfirm) {
            passwordField.value = encryptedPassword;
            confirmField.value = encryptedConfirm;
        }
    }
});
</script>

<?php

declare(strict_types=1);

/**
 * 个人中心页面
 */

require_once __DIR__ . '/includes/init.php';

// 要求登录
require_login();

// 获取当前用户信息
$user = get_logged_in_user();

$page_title = '个人中心';
require_once TEMPLATES_PATH . '/header.php';
require_once TEMPLATES_PATH . '/alerts.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">个人信息</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex justify-content-center align-items-center"
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        <?= escape_html(mb_substr($user['username'], 0, 1)) ?>
                    </div>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted" style="width: 120px;">用户名</th>
                        <td><?= escape_html($user['username']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">邮箱</th>
                        <td><?= escape_html($user['email']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">注册时间</th>
                        <td><?= escape_html($user['created_at']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="logout.php" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                    </svg>
                    退出登录
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>

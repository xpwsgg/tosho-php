<?php
declare(strict_types=1);

$flash_messages = get_flash();
$alert_types = [
    'success' => 'alert-success',
    'error' => 'alert-danger',
    'warning' => 'alert-warning',
    'info' => 'alert-info',
];

foreach ($flash_messages as $type => $message):
    $alert_class = $alert_types[$type] ?? 'alert-info';
?>
    <div class="alert <?= $alert_class ?> alert-dismissible fade show" role="alert">
        <?= escape_html($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
    </div>
<?php endforeach; ?>

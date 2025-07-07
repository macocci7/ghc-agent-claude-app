<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-x-fill text-danger" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title">401</h1>
                <h4 class="card-subtitle mb-4 text-muted">Unauthorized</h4>
                <p class="card-text">このページにアクセスするには認証が必要です。</p>
                <a href="<?= url('/login') ?>" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>ログイン
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = '401 Unauthorized - Login System';
include __DIR__ . '/../layouts/app.php';
?>

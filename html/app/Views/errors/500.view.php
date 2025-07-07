<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-exclamation-octagon-fill text-danger" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title">500</h1>
                <h4 class="card-subtitle mb-4 text-muted">Internal Server Error</h4>
                <p class="card-text">サーバー内部エラーが発生しました。</p>
                <a href="<?= url('/') ?>" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>ホームに戻る
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = '500 Internal Server Error - Login System';
include __DIR__ . '/../layouts/app.php';
?>

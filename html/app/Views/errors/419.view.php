<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-clock-history text-warning" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title mb-4">419 - ページの有効期限切れ</h1>
                <p class="card-text text-muted mb-4">
                    セッションが期限切れになりました。セキュリティのため、再度操作を行ってください。
                </p>
                
                <div class="d-grid gap-2">
                    <a href="<?= url('/') ?>" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>ホームに戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = '419 - ページの有効期限切れ - Login System';
include __DIR__ . '/../layouts/app.php';
?>

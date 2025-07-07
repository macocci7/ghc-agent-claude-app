<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-exclamation text-danger" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title mb-4">403 - アクセス拒否</h1>
                <p class="card-text text-muted mb-4">
                    このリクエストは許可されていません。CSRFトークンが無効または期限切れです。
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
$title = '403 - アクセス拒否 - Login System';
include __DIR__ . '/../layouts/app.php';
?>

<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title">404</h1>
                <h4 class="card-subtitle mb-4 text-muted">Page Not Found</h4>
                <p class="card-text">お探しのページは見つかりませんでした。</p>
                <a href="<?= url('/') ?>" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>ホームに戻る
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = '404 Not Found - Login System';
include __DIR__ . '/../layouts/app.php';
?>

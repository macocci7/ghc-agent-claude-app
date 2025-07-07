<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-lock-fill text-primary" style="font-size: 4rem;"></i>
                </div>
                <h1 class="card-title mb-4">ログインシステムへようこそ</h1>
                <p class="card-text text-muted mb-4">
                    アカウントをお持ちでない方は新規登録、既にアカウントをお持ちの方はログインしてください。
                </p>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= url('/signup') ?>" class="btn btn-primary btn-lg me-md-2">
                        <i class="bi bi-person-plus me-2"></i>サインアップ
                    </a>
                    <a href="<?= url('/login') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>ログイン
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'ホーム - Login System';
include __DIR__ . '/layouts/app.php';
?>

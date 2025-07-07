<?php
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <i class="bi bi-speedometer2 me-2"></i>ダッシュボード
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>ようこそ、<?= e($user['username']) ?>さん！</h5>
                        <p class="text-muted">ログインが完了しました。</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">アカウント情報</h6>
                                        <p class="card-text">
                                            <strong>ユーザー名:</strong> <?= e($user['username']) ?><br>
                                            <strong>メールアドレス:</strong> <?= e($user['email']) ?><br>
                                            <strong>登録日:</strong> <?= e($user['created_at']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">クイックアクション</h6>
                                        <div class="d-grid gap-2">
                                            <form method="POST" action="<?= url('/logout') ?>">
                                                <button type="submit" class="btn btn-warning btn-sm w-100">
                                                    <i class="bi bi-box-arrow-right me-2"></i>ログアウト
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-person-circle text-muted" style="font-size: 8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'ダッシュボード - Login System';
include __DIR__ . '/layouts/app.php';
?>

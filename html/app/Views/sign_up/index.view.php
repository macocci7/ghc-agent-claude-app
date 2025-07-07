<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>サインアップ
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('/signup') ?>">
                    <?= Csrf::field() ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">ユーザー名</label>
                        <input type="text" 
                               class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                               id="username" 
                               name="username" 
                               value="<?= e($input['username'] ?? '') ?>" 
                               required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= e($errors['username']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">メールアドレス</label>
                        <input type="email" 
                               class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= e($input['email'] ?? '') ?>" 
                               required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                        <div class="form-text">2文字目以降に@が含まれ、@で終わらないメールアドレスを入力してください。</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">パスワード</label>
                        <input type="password" 
                               class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                        <?php endif; ?>
                        <div class="form-text">英小文字と数字のみ、8文字以上12文字以下で入力してください。</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">パスワード（確認）</label>
                        <input type="password" 
                               class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                        <?php if (isset($errors['password_confirmation'])): ?>
                            <div class="invalid-feedback"><?= e($errors['password_confirmation']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>登録
                        </button>
                        <a href="<?= url('/') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>戻る
                        </a>
                    </div>
                </form>

                <hr>
                <div class="text-center">
                    <p class="mb-0">既にアカウントをお持ちですか？</p>
                    <a href="<?= url('/login') ?>" class="btn btn-link">ログインはこちら</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'サインアップ - Login System';
include __DIR__ . '/../layouts/app.php';
?>

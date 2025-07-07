<?php

namespace App\Logics;

use App\Models\UserModel;
use App\Models\LoginHistoryModel;
use App\Validators\UserValidator;

class LoginLogic extends LogicBase
{
    private UserModel $userModel;
    private LoginHistoryModel $loginHistoryModel;
    private UserValidator $validator;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->loginHistoryModel = new LoginHistoryModel();
        $this->validator = new UserValidator();
    }
    
    public function login(array $data): bool
    {
        $this->clearErrors();
        
        // バリデーション
        if (!$this->validator->validateLogin($data)) {
            $this->errors = $this->validator->getErrors();
            return false;
        }
        
        // ユーザー検索
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user) {
            $this->addError('general', 'メールアドレスまたはパスワードが間違っています。');
            return false;
        }
        
        // パスワード確認
        if (!$this->userModel->verifyPassword($user, $data['password'])) {
            $this->addError('general', 'メールアドレスまたはパスワードが間違っています。');
            return false;
        }
        
        try {
            // ログイン履歴記録
            $this->loginHistoryModel->createLoginHistory(
                $user['id'],
                getClientIp(),
                getUserAgent()
            );
            
            // 認証状態にする
            \Session::authenticate($user['id']);
            
            return true;
            
        } catch (Exception $e) {
            $this->addError('general', 'ログインに失敗しました。');
            return false;
        }
    }
}

<?php

namespace App\Logics;

use App\Models\UserModel;
use App\Validators\UserValidator;

class SignUpLogic extends LogicBase
{
    private UserModel $userModel;
    private UserValidator $validator;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validator = new UserValidator();
    }
    
    public function signUp(array $data): bool
    {
        $this->clearErrors();
        
        // バリデーション
        if (!$this->validator->validateSignUp($data)) {
            $this->errors = $this->validator->getErrors();
            return false;
        }
        
        try {
            // ユーザー作成
            $userId = $this->userModel->createUser(
                $data['username'],
                $data['email'],
                $data['password']
            );
            
            // 認証状態にする
            \Session::authenticate($userId);
            
            return true;
            
        } catch (Exception $e) {
            $this->addError('general', 'ユーザー登録に失敗しました。');
            return false;
        }
    }
}

<?php

namespace App\Validators;

use App\Models\UserModel;

class UserValidator extends ValidatorBase
{
    private UserModel $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function validateSignUp(array $data): bool
    {
        $this->clearErrors();
        
        $email = $data['email'] ?? '';
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $passwordConfirmation = $data['password_confirmation'] ?? '';
        
        // バリデーション実行
        $emailValid = $this->validateRequired('email', $email) && $this->validateEmail('email', $email);
        $usernameValid = $this->validateRequired('username', $username);
        $passwordValid = $this->validateRequired('password', $password) && $this->validatePassword('password', $password);
        $passwordConfirmationValid = $this->validatePasswordConfirmation($password, $passwordConfirmation);
        
        // ユニーク制約チェック
        if ($emailValid && $this->userModel->findByEmail($email)) {
            $this->addError('email', 'このメールアドレスは既に登録されています。');
        }
        
        if ($usernameValid && $this->userModel->findByUsername($username)) {
            $this->addError('username', 'このユーザー名は既に使用されています。');
        }
        
        return !$this->hasErrors();
    }
    
    public function validateLogin(array $data): bool
    {
        $this->clearErrors();
        
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        $emailValid = $this->validateRequired('email', $email) && $this->validateEmail('email', $email);
        $passwordValid = $this->validateRequired('password', $password) && $this->validatePassword('password', $password);
        
        return $emailValid && $passwordValid;
    }
}

<?php

namespace App\Validators;

abstract class ValidatorBase
{
    protected array $errors = [];
    
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
    
    public function clearErrors(): void
    {
        $this->errors = [];
    }
    
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }
    
    protected function validateRequired(string $field, $value): bool
    {
        if (empty($value)) {
            $this->addError($field, "{$field}は必須です。");
            return false;
        }
        return true;
    }
    
    protected function validateEmail(string $field, string $value): bool
    {
        // メールアドレスの２文字目以降に@が含まれており、@で終わらなければOK
        if (strlen($value) < 2 || strpos($value, '@', 1) === false || substr($value, -1) === '@') {
            $this->addError($field, "正しいメールアドレスを入力してください。");
            return false;
        }
        return true;
    }
    
    protected function validatePassword(string $field, string $value): bool
    {
        // パスワードに英小文字と数字だけが含まれ、8文字以上12文字以下であればOK
        if (strlen($value) < 8 || strlen($value) > 12) {
            $this->addError($field, "パスワードは8文字以上12文字以下で入力してください。");
            return false;
        }
        
        if (!preg_match('/^[a-z0-9]+$/', $value)) {
            $this->addError($field, "パスワードは英小文字と数字のみで入力してください。");
            return false;
        }
        
        return true;
    }
    
    protected function validatePasswordConfirmation(string $password, string $passwordConfirmation): bool
    {
        if ($password !== $passwordConfirmation) {
            $this->addError('password_confirmation', "パスワードが一致しません。");
            return false;
        }
        return true;
    }
}

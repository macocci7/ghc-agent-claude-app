<?php

namespace App\Validators;

class LoginHistoryValidator extends ValidatorBase
{
    public function validateLoginHistory(array $data): bool
    {
        $this->clearErrors();
        
        $userId = $data['user_id'] ?? '';
        $ip = $data['ip'] ?? '';
        $userAgent = $data['user_agent'] ?? '';
        
        $userIdValid = $this->validateRequired('user_id', $userId) && is_numeric($userId);
        $ipValid = $this->validateRequired('ip', $ip);
        
        if (!$userIdValid) {
            $this->addError('user_id', 'ユーザーIDが正しくありません。');
        }
        
        if (!$ipValid) {
            $this->addError('ip', 'IPアドレスが正しくありません。');
        }
        
        return !$this->hasErrors();
    }
}

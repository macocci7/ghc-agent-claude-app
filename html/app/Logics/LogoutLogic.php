<?php

namespace App\Logics;

class LogoutLogic extends LogicBase
{
    public function logout(): bool
    {
        try {
            \Session::logout();
            return true;
        } catch (Exception $e) {
            $this->addError('general', 'ログアウトに失敗しました。');
            return false;
        }
    }
}

<?php

namespace App\Controllers;

use App\Logics\LogoutLogic;

class LogoutController extends ControllerBase
{
    private LogoutLogic $logoutLogic;
    
    public function __construct()
    {
        $this->logoutLogic = new LogoutLogic();
    }
    
    public function logout(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        
        $this->logoutLogic->logout();
        \View::redirect('/login');
    }
}

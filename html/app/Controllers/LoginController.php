<?php

namespace App\Controllers;

use App\Logics\LoginLogic;

class LoginController extends ControllerBase
{
    private LoginLogic $loginLogic;
    
    public function __construct()
    {
        $this->loginLogic = new LoginLogic();
    }
    
    public function index(): void
    {
        $this->requireGuest();
        
        \View::render('login/index.view.php');
    }
    
    public function store(): void
    {
        $this->requireGuest();
        $this->verifyCsrf();
        
        $input = $this->getInput();
        
        if ($this->loginLogic->login($input)) {
            \View::redirect('/');
        } else {
            \View::render('login/index.view.php', [
                'errors' => $this->loginLogic->getErrors(),
                'input' => $input
            ]);
        }
    }
}

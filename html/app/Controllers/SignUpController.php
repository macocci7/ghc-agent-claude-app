<?php

namespace App\Controllers;

use App\Logics\SignUpLogic;

class SignUpController extends ControllerBase
{
    private SignUpLogic $signUpLogic;
    
    public function __construct()
    {
        $this->signUpLogic = new SignUpLogic();
    }
    
    public function index(): void
    {
        $this->requireGuest();
        
        \View::render('sign_up/index.view.php');
    }
    
    public function store(): void
    {
        $this->requireGuest();
        $this->verifyCsrf();
        
        $input = $this->getInput();
        
        if ($this->signUpLogic->signUp($input)) {
            \View::redirect('/dashboard');
        } else {
            \View::render('sign_up/index.view.php', [
                'errors' => $this->signUpLogic->getErrors(),
                'input' => $input
            ]);
        }
    }
}

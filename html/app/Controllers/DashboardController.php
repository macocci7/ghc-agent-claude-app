<?php

namespace App\Controllers;

use App\Models\UserModel;

class DashboardController extends ControllerBase
{
    private UserModel $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index(): void
    {
        $this->requireAuth();
        
        $userId = \Session::getUserId();
        $user = $this->userModel->findById($userId);
        
        \View::render('dashboard.view.php', [
            'user' => $user
        ]);
    }
}

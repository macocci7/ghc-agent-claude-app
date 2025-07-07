<?php

namespace App\Controllers;

class IndexController extends ControllerBase
{
    public function index(): void
    {
        // 認証済みの場合はダッシュボードへリダイレクト
        if (\Session::isAuthenticated()) {
            \View::redirect('/dashboard');
        }
        
        \View::render('index.view.php');
    }
}

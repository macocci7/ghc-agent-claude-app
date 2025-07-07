<?php

use App\Controllers\IndexController;
use App\Controllers\SignUpController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\DashboardController;

function defineRoutes(Router $router): void
{
    // トップページ
    $router->get('/', function() {
        $controller = new IndexController();
        $controller->index();
    });
    
    // サインアップ
    $router->get('/signup', function() {
        $controller = new SignUpController();
        $controller->index();
    });
    
    $router->post('/signup', function() {
        $controller = new SignUpController();
        $controller->store();
    });
    
    // ログイン
    $router->get('/login', function() {
        $controller = new LoginController();
        $controller->index();
    });
    
    $router->post('/login', function() {
        $controller = new LoginController();
        $controller->store();
    });
    
    // ログアウト
    $router->post('/logout', function() {
        $controller = new LogoutController();
        $controller->logout();
    });
    
    // ダッシュボード
    $router->get('/dashboard', function() {
        $controller = new DashboardController();
        $controller->index();
    });
}

<?php

namespace App\Controllers;

abstract class ControllerBase
{
    /**
     * @codeCoverageIgnore
     */
    protected function requireAuth(): void
    {
        if (!\Session::isAuthenticated()) {
            \View::redirect('/login');
        }
    }
    
    /**
     * @codeCoverageIgnore
     */
    protected function requireGuest(): void
    {
        if (\Session::isAuthenticated()) {
            \View::redirect('/dashboard');
        }
    }
    
    /**
     * Verify CSRF token for POST requests
     * @codeCoverageIgnore
     */
    protected function verifyCsrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\Csrf::verify()) {
                http_response_code(419);
                \View::render('errors/419.view.php');
                exit;
            }
        }
    }
    
    protected function getInput(): array
    {
        $input = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST;
        } else {
            $input = $_GET;
        }
        
        // Sanitize input
        return array_map(function($value) {
            return is_string($value) ? trim($value) : $value;
        }, $input);
    }
}

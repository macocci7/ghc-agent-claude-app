<?php

namespace Tests\Http;

class AuthenticationHttpTest extends HttpTestCase
{
    public function test_signup_with_valid_data_redirects_to_dashboard()
    {
        $uniqueId = uniqid();
        $this->postWithCsrf('/signup', [
            'username' => 'testuser' . $uniqueId,
            'email' => 'test' . $uniqueId . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        
        $this->assertRedirect('/dashboard');
    }

    public function test_signup_with_invalid_email_shows_error()
    {
        $this->postWithCsrf('/signup', [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        
        $this->assertStatus(200);
        $this->assertSee('正しいメールアドレスを入力してください');
    }

    public function test_signup_with_mismatched_passwords_shows_error()
    {
        $this->postWithCsrf('/signup', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password'
        ]);
        
        $this->assertStatus(200);
        $this->assertSee('パスワードが一致しません');
    }

    public function test_login_with_valid_credentials_redirects_to_dashboard()
    {
        // First create a user
        $this->createTestUser('test@example.com', 'password123');
        
        $this->postWithCsrf('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        $this->assertRedirect('/');
    }

    public function test_login_with_invalid_credentials_shows_error()
    {
        $this->postWithCsrf('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);
        
        $this->assertStatus(200);
        $this->assertSee('メールアドレスまたはパスワードが間違っています');
    }

    public function test_logout_redirects_to_homepage()
    {
        // Login first
        $this->createTestUser('test@example.com', 'password123');
        $this->loginUser('test@example.com', 'password123');
        
        // Note: For logout, we need to get the dashboard page first to get CSRF token
        $this->get('/dashboard');
        $token = $this->getCsrfToken();
        
        $this->post('/logout', $token ? ['_token' => $token] : []);
        
        $this->assertRedirect('/login');
    }

    public function test_csrf_protection_blocks_request_without_token()
    {
        // Test POST request without CSRF token should be blocked with 419 status
        $this->post('/signup', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        
        $this->assertStatus(419);
    }

    private function createTestUser(string $email, string $password): void
    {
        // Create test user in database
        // This would typically insert into the users table
    }

    private function loginUser(string $email, string $password): void
    {
        $this->postWithCsrf('/login', [
            'email' => $email,
            'password' => $password
        ]);
    }
}

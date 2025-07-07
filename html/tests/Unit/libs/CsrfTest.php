<?php

namespace Tests\Unit\Libs;

use PHPUnit\Framework\TestCase;
use Libs\Csrf;

class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Start session for testing if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Mock session for testing
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }
    
    protected function tearDown(): void
    {
        // Clean up session
        if (isset($_SESSION)) {
            $_SESSION = [];
        }
        
        parent::tearDown();
    }

    public function test_get_token_returns_session_id()
    {
        // Start session to generate session ID
        \Session::start();
        $sessionId = session_id();
        
        $token = Csrf::getToken();
        
        $this->assertEquals($sessionId, $token);
    }

    public function test_verify_token_with_valid_session_id()
    {
        \Session::start();
        $sessionId = session_id();
        
        $result = Csrf::verifyToken($sessionId);
        
        $this->assertTrue($result);
    }

    public function test_verify_token_with_invalid_token()
    {
        \Session::start();
        
        $result = Csrf::verifyToken('invalid_token');
        
        $this->assertFalse($result);
    }

    public function test_field_returns_hidden_input_with_session_id()
    {
        \Session::start();
        $sessionId = session_id();
        
        $field = Csrf::field();
        
        $this->assertStringContainsString('<input type="hidden"', $field);
        $this->assertStringContainsString('name="_token"', $field);
        $this->assertStringContainsString('value="' . $sessionId . '"', $field);
    }

    public function test_verify_without_post_data()
    {
        // No $_POST data
        $result = Csrf::verify();
        
        $this->assertFalse($result);
    }

    public function test_verify_with_valid_post_data()
    {
        // Ensure session is properly started and has an ID
        \Session::start();
        
        // Verify session has an ID
        $sessionId = session_id();
        $this->assertNotEmpty($sessionId, 'Session ID should not be empty');
        
        // Set the POST token to the session ID
        $_POST['_token'] = $sessionId;
        
        $result = Csrf::verify();
        
        $this->assertTrue($result, 'CSRF verification should pass with valid session ID');
        
        // Clean up
        unset($_POST['_token']);
    }

    public function test_verify_with_invalid_post_data()
    {
        \Session::start();
        $_POST['_token'] = 'invalid_token';
        
        $result = Csrf::verify();
        
        $this->assertFalse($result);
        
        // Clean up
        unset($_POST['_token']);
    }

    public function test_get_token_name()
    {
        $name = Csrf::getTokenName();
        
        $this->assertEquals('_token', $name);
    }

    public function test_verify_with_empty_token()
    {
        \Session::start();
        $_POST['_token'] = '';
        
        $result = Csrf::verify();
        
        $this->assertFalse($result);
        
        // Clean up
        unset($_POST['_token']);
    }
}

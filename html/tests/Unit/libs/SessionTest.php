<?php

namespace Tests\Unit\Libs;

use PHPUnit\Framework\TestCase;
use Libs\Session;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear all session data before each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        // Clean up session after each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
    }

    public function test_set_and_get_session_value()
    {
        Session::set('test_key', 'test_value');
        $this->assertEquals('test_value', Session::get('test_key'));
    }

    public function test_get_with_default_value()
    {
        $this->assertEquals('default', Session::get('nonexistent_key', 'default'));
    }

    public function test_has_returns_true_for_existing_key()
    {
        Session::set('existing_key', 'value');
        $this->assertTrue(Session::has('existing_key'));
    }

    public function test_has_returns_false_for_nonexistent_key()
    {
        $this->assertFalse(Session::has('nonexistent_key'));
    }

    public function test_remove_deletes_session_value()
    {
        Session::set('key_to_remove', 'value');
        Session::remove('key_to_remove');
        $this->assertFalse(Session::has('key_to_remove'));
    }

    public function test_is_authenticated_returns_false_by_default()
    {
        $this->assertFalse(Session::isAuthenticated());
    }

    public function test_authenticate_sets_authentication_data()
    {
        Session::authenticate(123);
        $this->assertTrue(Session::isAuthenticated());
        $this->assertEquals(123, Session::getUserId());
    }

    public function test_logout_clears_authentication_data()
    {
        Session::authenticate(123);
        Session::logout();
        $this->assertFalse(Session::isAuthenticated());
        $this->assertNull(Session::getUserId());
    }

    public function test_get_user_id_returns_null_when_not_authenticated()
    {
        $this->assertNull(Session::getUserId());
    }

    public function test_destroy_clears_session_data()
    {
        Session::set('test_key', 'test_value');
        Session::authenticate(123);
        
        Session::destroy();
        
        // After destroy, session should be cleared
        $this->assertEquals('default', Session::get('test_key', 'default'));
        $this->assertFalse(Session::isAuthenticated());
        $this->assertNull(Session::getUserId());
    }
}

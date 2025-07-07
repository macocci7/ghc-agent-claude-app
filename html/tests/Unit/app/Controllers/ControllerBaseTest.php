<?php

namespace Tests\Unit\App\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\ControllerBase;

class ControllerBaseTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        // Create an anonymous class that extends ControllerBase for testing
        $this->controller = new class extends ControllerBase {
            public function testRequireAuth(): void
            {
                $this->requireAuth();
            }
            
            public function testRequireGuest(): void
            {
                $this->requireGuest();
            }
            
            public function testGetInput(): array
            {
                return $this->getInput();
            }
        };

        // Reset session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
    }

    public function test_get_input_returns_post_data_for_post_request()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => 'John', 'email' => 'john@example.com'];

        $result = $this->controller->testGetInput();

        $this->assertEquals(['name' => 'John', 'email' => 'john@example.com'], $result);
    }

    public function test_get_input_returns_get_data_for_get_request()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['search' => 'query', 'page' => '1'];

        $result = $this->controller->testGetInput();

        $this->assertEquals(['search' => 'query', 'page' => '1'], $result);
    }

    public function test_get_input_trims_string_values()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => '  John  ', 'age' => 25];

        $result = $this->controller->testGetInput();

        $this->assertEquals(['name' => 'John', 'age' => 25], $result);
    }

    public function test_require_auth_passes_when_authenticated()
    {
        \Session::set('authenticated', true);

        // Should not throw any exception
        $this->controller->testRequireAuth();
        $this->assertTrue(true); // If we reach here, the test passes
    }

    public function test_require_guest_passes_when_not_authenticated()
    {
        \Session::set('authenticated', false);

        // Should not throw any exception
        $this->controller->testRequireGuest();
        $this->assertTrue(true); // If we reach here, the test passes
    }
}

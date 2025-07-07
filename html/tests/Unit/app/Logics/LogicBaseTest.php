<?php

namespace Tests\Unit\App\Logics;

use PHPUnit\Framework\TestCase;
use App\Logics\LogicBase;

class LogicBaseTest extends TestCase
{
    private $logic;

    protected function setUp(): void
    {
        // Create an anonymous class that extends LogicBase for testing
        $this->logic = new class extends LogicBase {
            public function testAddError(string $key, string $message): void
            {
                $this->addError($key, $message);
            }

            public function testClearErrors(): void
            {
                $this->clearErrors();
            }
        };
    }

    public function test_get_errors_returns_empty_array_initially()
    {
        $this->assertEmpty($this->logic->getErrors());
    }

    public function test_has_errors_returns_false_initially()
    {
        $this->assertFalse($this->logic->hasErrors());
    }

    public function test_add_error_adds_error_to_collection()
    {
        $this->logic->testAddError('login', 'Invalid credentials');
        
        $this->assertTrue($this->logic->hasErrors());
        $this->assertEquals(['login' => 'Invalid credentials'], $this->logic->getErrors());
    }

    public function test_add_multiple_errors()
    {
        $this->logic->testAddError('email', 'Email is required');
        $this->logic->testAddError('password', 'Password is required');
        
        $expected = [
            'email' => 'Email is required',
            'password' => 'Password is required'
        ];
        
        $this->assertTrue($this->logic->hasErrors());
        $this->assertEquals($expected, $this->logic->getErrors());
    }

    public function test_clear_errors_removes_all_errors()
    {
        $this->logic->testAddError('test', 'Test error');
        $this->logic->testClearErrors();
        
        $this->assertFalse($this->logic->hasErrors());
        $this->assertEmpty($this->logic->getErrors());
    }

    public function test_add_error_overwrites_existing_error_for_same_key()
    {
        $this->logic->testAddError('email', 'First error');
        $this->logic->testAddError('email', 'Second error');
        
        $this->assertEquals(['email' => 'Second error'], $this->logic->getErrors());
    }
}

<?php

namespace Tests\Unit\App\Validators;

use PHPUnit\Framework\TestCase;
use App\Validators\ValidatorBase;

class ValidatorBaseTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        // Create an anonymous class that extends ValidatorBase for testing
        $this->validator = new class extends ValidatorBase {
            public function testValidateRequired(string $field, $value): bool
            {
                return $this->validateRequired($field, $value);
            }

            public function testValidateEmail(string $field, string $value): bool
            {
                return $this->validateEmail($field, $value);
            }

            public function testValidatePassword(string $field, string $value): bool
            {
                return $this->validatePassword($field, $value);
            }

            public function testValidatePasswordConfirmation(string $password, string $passwordConfirmation): bool
            {
                return $this->validatePasswordConfirmation($password, $passwordConfirmation);
            }

            public function testAddError(string $field, string $message): void
            {
                $this->addError($field, $message);
            }
        };
    }

    public function test_get_errors_returns_empty_array_initially()
    {
        $this->assertEmpty($this->validator->getErrors());
    }

    public function test_has_errors_returns_false_initially()
    {
        $this->assertFalse($this->validator->hasErrors());
    }

    public function test_add_error_adds_error_to_collection()
    {
        $this->validator->testAddError('email', 'Invalid email');
        
        $this->assertTrue($this->validator->hasErrors());
        $this->assertEquals(['email' => 'Invalid email'], $this->validator->getErrors());
    }

    public function test_clear_errors_removes_all_errors()
    {
        $this->validator->testAddError('email', 'Invalid email');
        $this->validator->clearErrors();
        
        $this->assertFalse($this->validator->hasErrors());
        $this->assertEmpty($this->validator->getErrors());
    }

    public function test_validate_required_passes_for_non_empty_value()
    {
        $result = $this->validator->testValidateRequired('name', 'John');
        
        $this->assertTrue($result);
        $this->assertFalse($this->validator->hasErrors());
    }

    public function test_validate_required_fails_for_empty_value()
    {
        $result = $this->validator->testValidateRequired('name', '');
        
        $this->assertFalse($result);
        $this->assertTrue($this->validator->hasErrors());
        $this->assertEquals(['name' => 'nameは必須です。'], $this->validator->getErrors());
    }

    public function test_validate_email_passes_for_valid_email()
    {
        $validEmails = [
            'test@example.com',
            'user@domain.org',
            'name.lastname@company.co.jp'
        ];

        foreach ($validEmails as $email) {
            $this->validator->clearErrors();
            $result = $this->validator->testValidateEmail('email', $email);
            
            $this->assertTrue($result, "Failed for email: $email");
            $this->assertFalse($this->validator->hasErrors());
        }
    }

    public function test_validate_email_fails_for_invalid_email()
    {
        $invalidEmails = [
            'invalid',
            '@domain.com',
            'user@',
            'a@',
            '@'
        ];

        foreach ($invalidEmails as $email) {
            $this->validator->clearErrors();
            $result = $this->validator->testValidateEmail('email', $email);
            
            $this->assertFalse($result, "Should fail for email: $email");
            $this->assertTrue($this->validator->hasErrors());
        }
    }

    public function test_validate_password_passes_for_valid_password()
    {
        $validPasswords = [
            'password123',
            'test1234',
            'abcdefgh'
        ];

        foreach ($validPasswords as $password) {
            $this->validator->clearErrors();
            $result = $this->validator->testValidatePassword('password', $password);
            
            $this->assertTrue($result, "Failed for password: $password");
            $this->assertFalse($this->validator->hasErrors());
        }
    }

    public function test_validate_password_fails_for_invalid_length()
    {
        $this->validator->clearErrors();
        $result = $this->validator->testValidatePassword('password', 'short');
        
        $this->assertFalse($result);
        $this->assertTrue($this->validator->hasErrors());
        $this->assertStringContainsString('8文字以上12文字以下', $this->validator->getErrors()['password']);
    }

    public function test_validate_password_fails_for_invalid_characters()
    {
        $this->validator->clearErrors();
        $result = $this->validator->testValidatePassword('password', 'Password123');
        
        $this->assertFalse($result);
        $this->assertTrue($this->validator->hasErrors());
        $this->assertStringContainsString('英小文字と数字のみ', $this->validator->getErrors()['password']);
    }

    public function test_validate_password_confirmation_passes_for_matching_passwords()
    {
        $result = $this->validator->testValidatePasswordConfirmation('password123', 'password123');
        
        $this->assertTrue($result);
        $this->assertFalse($this->validator->hasErrors());
    }

    public function test_validate_password_confirmation_fails_for_non_matching_passwords()
    {
        $result = $this->validator->testValidatePasswordConfirmation('password123', 'different123');
        
        $this->assertFalse($result);
        $this->assertTrue($this->validator->hasErrors());
        $this->assertEquals(['password_confirmation' => 'パスワードが一致しません。'], $this->validator->getErrors());
    }
}

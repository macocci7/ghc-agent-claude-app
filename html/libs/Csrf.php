<?php

namespace Libs;

class Csrf
{
    private const TOKEN_NAME = '_token';
    
    /**
     * Get the current session ID as CSRF token
     */
    public static function getToken(): string
    {
        Session::start();
        return session_id();
    }
    
    /**
     * Verify a CSRF token against the session ID
     */
    public static function verifyToken(string $token): bool
    {
        Session::start();
        return $token === session_id();
    }
    
    /**
     * Generate CSRF token input field for forms
     */
    public static function field(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Verify CSRF token from request
     */
    public static function verify(): bool
    {
        $token = $_POST[self::TOKEN_NAME] ?? '';
        
        if (empty($token)) {
            return false;
        }
        
        return self::verifyToken($token);
    }
    
    /**
     * Get the token name for forms
     */
    public static function getTokenName(): string
    {
        return self::TOKEN_NAME;
    }
}

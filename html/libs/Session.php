<?php

namespace Libs;

class Session
{
    private static bool $started = false;
    
    public static function start(): void
    {
        if (!self::$started) {
            session_start();
            self::$started = true;
        }
    }
    
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function destroy(): void
    {
        self::start();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy(); // @codeCoverageIgnore
        }
        self::$started = false;
    }
    
    public static function isAuthenticated(): bool
    {
        return self::get('authenticated', false) === true;
    }
    
    public static function authenticate(int $userId): void
    {
        self::set('authenticated', true);
        self::set('user_id', $userId);
    }
    
    public static function logout(): void
    {
        self::set('authenticated', false);
        self::remove('user_id');
    }
    
    public static function getUserId(): ?int
    {
        return self::isAuthenticated() ? self::get('user_id') : null;
    }
}

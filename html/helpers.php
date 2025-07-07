<?php

/**
 * Get configuration value by key
 */
function config(string $key)
{
    $parts = explode('.', $key);
    $config = require __DIR__ . '/config/' . $parts[0] . '.php';
    
    foreach (array_slice($parts, 1) as $part) {
        $config = $config[$part] ?? null;
    }
    
    return $config;
}

/**
 * Escape HTML
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate URL
 */
function url(string $path = ''): string
{
    $baseUrl = config('app.url');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Get client IP address
 */
function getClientIp(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

/**
 * Get user agent
 */
function getUserAgent(): string
{
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

/**
 * Autoloader for classes
 */
function autoload(string $className): void
{
    // Convert namespace to file path
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // Check in libs directory
    $libsPath = __DIR__ . '/libs/' . $className . '.php';
    if (file_exists($libsPath)) {
        require_once $libsPath;
        return;
    }
    
    // Check in app directory
    if (strpos($className, 'App/') === 0) {
        $appPath = __DIR__ . '/app/' . substr($className, 4) . '.php';
        if (file_exists($appPath)) {
            require_once $appPath;
            return;
        }
    }
    
    // Check in current directory
    $currentPath = __DIR__ . '/' . $className . '.php';
    if (file_exists($currentPath)) {
        require_once $currentPath;
    }
}

spl_autoload_register('autoload');

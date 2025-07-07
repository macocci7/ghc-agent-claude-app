<?php

namespace Libs;

use Exception;

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        
        $templatePath = __DIR__ . '/../app/Views/' . $template;
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: $templatePath");
        }
        
        include $templatePath;
    }
    
    /**
     * @codeCoverageIgnore
     */
    public static function redirect(string $url, int $code = 302): void
    {
        http_response_code($code);
        header("Location: $url");
        exit;
    }
    
    /**
     * @codeCoverageIgnore
     */
    public static function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

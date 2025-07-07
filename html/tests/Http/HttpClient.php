<?php

namespace Tests\Http;

class HttpClient
{
    private string $baseUrl;
    private array $headers = [];
    private array $cookies = [];

    public function __construct(string $baseUrl = 'http://localhost')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function get(string $path, array $params = []): HttpResponse
    {
        $url = $this->baseUrl . $path;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->makeRequest('GET', $url);
    }

    public function post(string $path, array $data = []): HttpResponse
    {
        $url = $this->baseUrl . $path;
        
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $this->buildHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]),
                'content' => http_build_query($data),
                'ignore_errors' => true
            ]
        ]);

        return $this->makeRequestWithContext($url, $context);
    }

    private function makeRequest(string $method, string $url): HttpResponse
    {
        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'header' => $this->buildHeaders(),
                'ignore_errors' => true
            ]
        ]);

        return $this->makeRequestWithContext($url, $context);
    }

    private function makeRequestWithContext(string $url, $context): HttpResponse
    {
        $content = file_get_contents($url, false, $context);
        
        $headers = [];
        if (isset($http_response_header)) {
            $headers = $http_response_header;
            
            // Extract and store cookies from Set-Cookie headers
            $this->extractCookies($headers);
        }
        
        // If content is false but we have headers, it might be an HTTP error
        // In that case, return empty content with the headers (which contain status code)
        if ($content === false) {
            $content = '';
        }

        return new HttpResponse($content, $headers);
    }
    
    private function extractCookies(array $headers): void
    {
        foreach ($headers as $header) {
            if (stripos($header, 'Set-Cookie:') === 0) {
                $cookieData = substr($header, 11);
                $cookieParts = explode(';', $cookieData);
                $cookieNameValue = trim($cookieParts[0]);
                
                if (strpos($cookieNameValue, '=') !== false) {
                    list($name, $value) = explode('=', $cookieNameValue, 2);
                    $this->setCookie(trim($name), trim($value));
                }
            }
        }
    }

    private function buildHeaders(array $additional = []): string
    {
        $headers = array_merge($this->headers, $additional);
        $headerLines = [];
        
        foreach ($headers as $name => $value) {
            $headerLines[] = "$name: $value";
        }
        
        if (!empty($this->cookies)) {
            $cookieString = [];
            foreach ($this->cookies as $name => $value) {
                $cookieString[] = "$name=$value";
            }
            $headerLines[] = 'Cookie: ' . implode('; ', $cookieString);
        }
        
        return implode("\r\n", $headerLines);
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function setCookie(string $name, string $value): void
    {
        $this->cookies[$name] = $value;
    }
}

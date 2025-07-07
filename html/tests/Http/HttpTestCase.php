<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;

abstract class HttpTestCase extends TestCase
{
    protected HttpClient $client;
    protected ?HttpResponse $response = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new HttpClient('http://127.0.0.1');
    }

    protected function get(string $path, array $params = []): HttpResponse
    {
        $this->response = $this->client->get($path, $params);
        return $this->response;
    }

    protected function post(string $path, array $data = []): HttpResponse
    {
        $this->response = $this->client->post($path, $data);
        return $this->response;
    }

    protected function assertStatus(int $expectedStatus): void
    {
        $this->assertNotNull($this->response, 'No HTTP response available. Make a request first.');
        $actualStatus = $this->response->getStatusCode();
        $this->assertEquals(
            $expectedStatus,
            $actualStatus,
            "Expected HTTP status code {$expectedStatus}, but got {$actualStatus}"
        );
    }

    protected function assertOk(): void
    {
        $this->assertStatus(200);
    }

    protected function assertRedirect(?string $expectedUrl = null): void
    {
        $this->assertNotNull($this->response, 'No HTTP response available. Make a request first.');
        
        $statusCode = $this->response->getStatusCode();
        $this->assertTrue(
            in_array($statusCode, [300, 301, 302, 303, 307, 308]),
            "Expected redirect status code (300-308), but got {$statusCode}"
        );

        if ($expectedUrl !== null) {
            $locationHeader = $this->response->getLocationHeader();
            $this->assertEquals(
                $expectedUrl,
                $locationHeader,
                "Expected redirect to '{$expectedUrl}', but got '{$locationHeader}'"
            );
        }
    }

    protected function assertSee(string $needle): void
    {
        $this->assertNotNull($this->response, 'No HTTP response available. Make a request first.');
        $body = $this->response->getBody();
        $this->assertStringContainsString(
            $needle,
            $body,
            "Expected to see '{$needle}' in response body"
        );
    }

    protected function assertDontSee(string $needle): void
    {
        $this->assertNotNull($this->response, 'No HTTP response available. Make a request first.');
        $body = $this->response->getBody();
        $this->assertStringNotContainsString(
            $needle,
            $body,
            "Expected NOT to see '{$needle}' in response body"
        );
    }

    /**
     * Extract CSRF token from HTML response
     */
    protected function getCsrfToken(): ?string
    {
        if (!$this->response) {
            return null;
        }

        $body = $this->response->getBody();
        
        // Look for hidden input with name="_token"
        if (preg_match('/name="_token"\s+value="([^"]+)"/', $body, $matches)) {
            return $matches[1];
        }
        
        // Alternative pattern
        if (preg_match('/value="([^"]+)"\s+name="_token"/', $body, $matches)) {
            return $matches[1];
        }
        
        // More flexible pattern
        if (preg_match('/<input[^>]+name="_token"[^>]+value="([^"]+)"[^>]*>/', $body, $matches)) {
            return $matches[1];
        }
        
        // Reverse order
        if (preg_match('/<input[^>]+value="([^"]+)"[^>]+name="_token"[^>]*>/', $body, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Make a POST request with CSRF token
     */
    protected function postWithCsrf(string $path, array $data = []): HttpResponse
    {
        // First get the form page to extract CSRF token
        $formPath = $path === '/login' ? '/login' : ($path === '/signup' ? '/signup' : '/');
        $this->get($formPath);
        
        $token = $this->getCsrfToken();
        if ($token) {
            $data['_token'] = $token;
        }
        
        return $this->post($path, $data);
    }
}

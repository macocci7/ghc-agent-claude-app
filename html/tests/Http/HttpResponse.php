<?php

namespace Tests\Http;

class HttpResponse
{
    private string $body;
    private array $headers;
    private int $statusCode;

    public function __construct(string $body, array $headers)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $this->parseStatusCode($headers);
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeader(string $name): ?string
    {
        $name = strtolower($name);
        foreach ($this->headers as $header) {
            if (strpos($header, ':') !== false) {
                list($headerName, $headerValue) = explode(':', $header, 2);
                if (strtolower(trim($headerName)) === $name) {
                    return trim($headerValue);
                }
            }
        }
        return null;
    }

    public function getLocationHeader(): ?string
    {
        return $this->getHeader('location');
    }

    private function parseStatusCode(array $headers): int
    {
        if (empty($headers)) {
            return 200;
        }

        $statusLine = $headers[0];
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $statusLine, $matches)) {
            return (int) $matches[1];
        }

        return 200;
    }
}

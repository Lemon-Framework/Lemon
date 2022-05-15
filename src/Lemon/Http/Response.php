<?php

declare(strict_types=1);

namespace Lemon\Http;

/**
 * Represents Http Response.
 *
 * TODO fancy methods for manipulation
 *
 */
abstract class Response
{

    public function __construct(
        protected mixed $body = '',
        protected int $status_code = 200,
        protected array $headers = []
    ) {
        
    }

    public function send()
    {
        $this->handleHeaders();
        $this->handleStatusCode();
        $this->handleBody();
    }

    public function header(string $key, string $value=null): ?string
    {
        if (!$value) {
            return $this->headers[$key] ?? null;
        }

        $this->headers[$key] = $value;
        return null;
    } 

    private function handleStatusCode()
    {
        http_response_code($this->status_code);
    }

    private function handleHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header($header.':'.$value);
        }
    }

    abstract protected function handleBody(): void;

}

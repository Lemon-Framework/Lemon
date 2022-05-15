<?php

declare(strict_types=1);

namespace Lemon\Http;

/**
 * Represents Http Response.
 *
 * TODO fancy methods for manipulation
 */
abstract class Response
{
    public function __construct(
        protected mixed $body = '',
        protected int $status_code = 200,
        protected array $headers = []
    ) {
    }

    public function send(): static
    {
        $this->handleHeaders();
        $this->handleStatusCode();
        $this->handleBody();

        return $this;
    }

    public function header(string $key, string $value = null): ?string
    {
        if (!$value) {
            return $this->headers[$key] ?? null;
        }

        $this->headers[$key] = $value;

        return null;
    }

    public function location(string $location): static
    {
        $this->header('Location', $location);

        return $this;
    }

    public function code(int $code = null): static|int
    {
        if (!$code) {
            return $this->status_code;
        }

        $this->status_code = $code;

        return $this;
    }

    abstract protected function handleBody(): void;

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
}

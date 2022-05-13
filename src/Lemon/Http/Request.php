<?php

// TODO ADD:
// getting via decorators
// LITERALY THE WHOLE THIN
/*

    method
    path
    headers
    body -> by content type ig and everything

 */

declare(strict_types=1);

namespace Lemon\Http;

class Request
{
    public function __construct(
        public readonly string $path,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body
    ) {
    }

    public static function capture(): self
    {
        return new self(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD'],
            headers_list(),
            file_get_contents('php://input')
        );
    }
}

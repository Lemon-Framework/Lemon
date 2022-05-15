<?php

// TODO ADD:
// getting via decorators
// LITERALY THE WHOLE THIN
// body -> by content type ig and everything

declare(strict_types=1);

namespace Lemon\Http;

class Request
{
    public function __construct(
        public readonly string $path,
        public readonly string $query,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body
    ) {
    }

    public static function capture(): self
    {
        [$path, $query] = static::trimQuery($_SERVER['REQUEST_URI']);

        return new self(
            $path,
            $query,
            $_SERVER['REQUEST_METHOD'],
            static::parseHeaders(headers_list()),
            file_get_contents('php://input')
        );
    }

    /**
     * Splits path into path and query.
     */
    public static function trimQuery(string $path)
    {
        if (preg_match('/^(.+?)\?(.+)$/', $path, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return [$path, ''];
    }

    /**
     * Converts string headers into key-value array.
     *
     * @param array<string> $headers
     *
     * @return array<string, string>
     */
    public static function parseHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $header) {
            [$key, $value] = explode(' ', $header);
            $result[$key] = $value;
        }

        return $result;
    }

    public function header(string $name): string
    {
        return $this->headers[$name];
    }

    public function parseBody()
    {
    }
}

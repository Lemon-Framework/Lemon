<?php

// TODO ADD:
// getting via decorators
// LITERALY THE WHOLE THIN
// body -> by content type ig and everything

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Kernel\Lifecycle;
use Lemon\Validation\Validator;

class Request
{
    private array $data = null;

    private Lifecycle $lifecycle = null;

    public function __construct(
        public readonly string $path,
        public readonly string $query,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body
    ) {
    }

    public function injectLifecycle(Lifecycle $lifecycle): static
    {
        $this->lifecycle = $lifecycle;
        return $this;
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

    public function header(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function parseBody()
    {
        $this->data = [];
        if (!$content_type = $this->header('Content-Type')) {
            return;
        }

        switch ($content_type) {
            case 'application/x-www-form-urlencoded': 
                parse_str($this->body, $result);
                $this->data = $result;
                return;
            case 'application/json':
                $this->data = json_decode($this->body);
                return;
        }

    }

    public function data()
    {
        if (is_null($this->data)) {
            $this->parseBody();
        }
        return $this->data;
    }

    public function get(string $key): ?string
    {
        return $this->data()[$key] ?? null;
    }

    public function validate(array $rules): bool
    {
        if (!$this->lifecycle) {
            throw new Exception('Lifecycle is required for validation. Try injecting using ::injectLifecycle'); // TODO exception
        }
        
        return $this->lifecycle->get(Validator::class)
                        ->validate($this->data, $rules);
    }
}

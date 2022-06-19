<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Kernel\Lifecycle;
use Lemon\Validation\Validator;

class Request
{
    private ?array $post_data = null;

    private ?array $get_data = null;

    private ?Lifecycle $lifecycle = null;

    private array $parsers = [];

    public function __construct(
        public readonly string $path,
        public readonly string $query,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body
    ) {
    }

    public function __get($name)
    {
        if ($result = $this->get($name)) {
            return $result;
        }

        throw new Exception('Property '.$name.' does not exist');
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
    public static function trimQuery(string $path): array
    {
        $url = parse_url($path);

        if (isset($url['query'])) {
            return [$url['path'], $url['query']];
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

    public function hasHeader(string $header): bool
    {
        return isset($this->headers[$header]);
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function is(string $content_type): bool
    {
        return $this->header('Content-Type') === $content_type;
    }

    private function parseBody()
    {
        $this->post_data = [];
        if (!$content_type = $this->header('Content-Type')) {
            return;
        }

        switch ($content_type) {
            case 'application/x-www-form-urlencoded':
                parse_str($this->body, $result);
                $this->post_data = $result;

                return;

            case 'application/json':
                $this->post_data = json_decode($this->body);

                return;

            default:
                if (isset($this->parsers[$content_type])) {
                    $this->post_data = $this->parsers[$content_type]();
                }
        }
    }

    public function addParser(string $content_type, callable $parser): static
    {
        $this->parsers[$content_type] = $parser;

        return $this;
    }

    public function data()
    {
        if (is_null($this->post_data)) {
            $this->parseBody();
        }

        return $this->post_data;
    }

    public function get(string $key): ?string
    {
        return $this->data()[$key] ?? null;
    }

    public function query(?string $key): ?string
    {
        if (!$key) {
            return $this->query;
        }
        
        if (is_null($this->get_data)) {
            parse_str($this->query, $this->get_data);
        }

        return $this->query[$key] ?? null;
    }

    public function validate(array $rules): bool
    {
        if (!$this->lifecycle) {
            throw new Exception('Lifecycle is required for validation. Try injecting using ::injectLifecycle'); // TODO exception
        }

        return $this->lifecycle->get(Validator::class)
            ->validate($this->data, $rules)
        ;
    }

    public function toArray(): array
    {
        return []; // TODO
    }
}

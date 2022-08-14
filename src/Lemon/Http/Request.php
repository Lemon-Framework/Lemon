<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Kernel\Application;
use Lemon\Validation\Validator;

class Request
{
    private ?array $body_data = null;

    private ?array $query_data = null;

    private ?Application $lifecycle = null;

    private array $parsers = [];

    public function __construct(
        public readonly string $path,
        public readonly string $query,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body,
        public readonly array $cookies,
    ) {
    }

    public function __get($name)
    {
        if ($result = $this->get($name)) {
            return $result;
        }

        throw new Exception('Property '.$name.' does not exist');
    }

    /**
     * Creates new instance of actual sent request.
     */
    public static function capture(): self
    {
        [$path, $query] = static::trimQuery($_SERVER['REQUEST_URI']);

        return new self(
            $path,
            $query,
            $_SERVER['REQUEST_METHOD'],
            getallheaders(),
            file_get_contents('php://input'),
            $_COOKIE
        );
    }

    /**
     * Injects lifecycle.
     */
    public function injectLifecycle(Application $lifecycle): static
    {
        $this->lifecycle = $lifecycle;

        return $this;
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
     * Returns header with given name, null if header dont exist.
     */
    public function header(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Returns whenever header exists.
     */
    public function hasHeader(string $header): bool
    {
        return isset($this->headers[$header]);
    }

    /**
     * Returns all headers.
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Returns whenever request has given content type.
     */
    public function is(string $content_type): bool
    {
        return $this->header('Content-Type') === $content_type;
    }

    /**
     * Adds request parsing function.
     */
    public function addParser(string $content_type, callable $parser): static
    {
        $this->parsers[$content_type] = $parser;

        return $this;
    }

    /**
     * Returns array of parsed request body.
     *
     * @return array<string, string>
     */
    public function data(): array
    {
        if (is_null($this->body_data)) {
            $this->parseBody();
        }

        return $this->body_data;
    }

    /**
     * Returns request body value for given key.
     */
    public function get(string $key): ?string
    {
        return $this->data()[$key] ?? null;
    }

    /**
     * If key is null, returns parsed query, otherwise value for given key in parsed query or null if not found.
     */
    public function query(?string $key = null): string|array|null
    {
        if (!empty($this->query)) {
            parse_str($this->query, $this->query_data);
        }

        if (!$key) {
            return $this->query_data;
        }

        return $this->query_data[$key] ?? null;
    }

    /**
     * Determins whenever request meets given rules.
     *
     * @throws Exception When lifecycle is not injected
     */
    public function validate(array $rules): bool
    {
        if (!$this->lifecycle) {
            throw new Exception('Lifecycle is required for validation. Try injecting using ::injectLifecycle'); // TODO exception
        }

        return $this->lifecycle->get(Validator::class)
            ->validate($this->data(), $rules)
        ;
    }

    public function getCookie(string $name): ?string
    {
        return $this->cookies[$name] ?? null;
    }

    public function hasCookie(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * Returns array from request.
     */
    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'query' => $this->query,
            'method' => $this->method,
            'headers' => $this->headers,
            'body' => $this->body,
        ];
    }

    private function parseBody()
    {
        $this->body_data = [];
        if (!$content_type = $this->header('Content-Type')) {
            return;
        }

        switch ($content_type) {
            case 'application/x-www-form-urlencoded':
                parse_str($this->body, $result);
                $this->body_data = $result;

                return;

            case 'application/json':
                $this->body_data = json_decode($this->body, true);

                return;

            default:
                if (isset($this->parsers[$content_type])) {
                    $this->body_data = $this->parsers[$content_type]($this->body);
                }
        }
    }
}

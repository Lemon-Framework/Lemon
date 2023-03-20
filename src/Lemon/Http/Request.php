<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Contracts\Validation\Validator;
use Lemon\DataMapper\DataMapper;
use Lemon\Kernel\Application;

class Request
{
    private ?array $body_data = null;

    private ?array $query_data = null;

    private ?Application $application = null;

    private array $parsers = [];

    public function __construct(
        public readonly string $path,
        public readonly string $query,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $body,
        public readonly array $cookies,
        public readonly array $files,
        public readonly string $ip
    ) {
    }

    public function __get($name)
    {
        if (!is_null($result = $this->get($name))) {
            return $result;
        }

        throw new \Exception('Property '.$name.' does not exist');
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
            $_COOKIE,
            array_map(fn ($item) => new File(...$item), $_FILES),
            $_SERVER['REMOTE_ADDR']
        );
    }

    /**
     * Injects application.
     */
    public function injectApplication(Application $application): static
    {
        $this->application = $application;

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

    public function aint(string $content_type): bool
    {
        return !$this->is($content_type);
    }

    public function mustBe(string $content_type, mixed $fallback): static
    {
        if (!$this->is($content_type)) {
            \Fiber::suspend($fallback);
        }

        return $this;
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
    public function get(string $key): mixed
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
     * @throws \Exception When application is not injected
     */
    public function validate(array $rules, mixed $fallback): void
    {
        if (!$this->application) {
            throw new \Exception('Application is required for validation. Try injecting using ::injectApplication'); // TODO exception
        }

        if (!$this->application->get(Validator::class)
            ->validate($this->data(), $rules)
        ) {
            \Fiber::suspend($fallback);
        }
    }

    public function getCookie(string $name): ?string
    {
        return $this->cookies[$name] ?? null;
    }

    public function hasCookie(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    public function cookies(): array
    {
        return $this->cookies;
    }

    public function file(string $name): ?File
    {
        return $this->files[$name] ?? null;
    }

    public function hasFile(string $name): bool
    {
        return isset($this->files[$name]);
    }

    /**
     * Returns client ip address
     * Disclaimer: If you want to work with ip address, be aware of security laws.
     */
    public function ip(): string
    {
        return $this->ip;
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

    public function mapTo(string $class, mixed $response = null): ?object
    {
        $result = DataMapper::mapTo($this->data(), $class);

        if (null === $result && null !== $response) {
            \Fiber::suspend($response);
        }

        return $result;
    }

    private function parseBody(): void
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

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
    public const ERROR_STATUS_CODES = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
    ];

    public function __construct(
        public mixed $body = '',
        public int $status_code = 200,
        private array $headers = []
    ) {
    }

    /**
     * Sends response data back to user.
     */
    public function send(): static
    {
        $body = $this->parseBody();
        $this->handleHeaders();
        $this->handleStatusCode();
        $this->handleBody($body);

        return $this;
    }

    /**
     * Gets/sets header.
     */
    public function header(string $key, string $value = null): string|static
    {
        if (!$value) {
            return $this->headers[$key] ?? null;
        }

        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Sets location.
     */
    public function location(string $location): static
    {
        $this->header('Location', $location);

        return $this;
    }

    /**
     * Redirects to given location.
     */
    public function redirect(string $to): static
    {
        return $this->location($to);
    }

    /**
     * Sets status code.
     */
    public function code(int $code = null): static|int
    {
        if (!$code) {
            return $this->status_code;
        }

        $this->status_code = $code;

        return $this;
    }

    /**
     * Sets body content.
     */
    public function body(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Parses body.
     */
    abstract public function parseBody(): string;

    /**
     * Sends status code.
     */
    public function handleStatusCode()
    {
        http_response_code($this->status_code);
    }

    /**
     * Sends headers.
     */
    public function handleHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header($header.': '.$value);
        }
    }

    /**
     * Sends body.
     */
    public function handleBody(string $body): void
    {
        echo $body;
    }

    /**
     * Returns all headers.
     *
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }
}

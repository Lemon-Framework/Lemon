<?php

declare(strict_types=1);

namespace Lemon\Http;

use Lemon\Kernel\Application;

/**
 * Represents Http Response.
 *
 * TODO fancy methods for manipulation
 */
abstract class Response
{
    public const STATUS_CODES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Checkpoint',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
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

    private array $cookies = [];

    public function __construct(
        public mixed $body = '',
        public int $status_code = 200,
        private array $headers = []
    ) {
    }

    public function __toString(): string
    {
        $status = self::STATUS_CODES[$this->status_code];

        foreach ($this->cookies as [$name, $cookie, $expire]) {
            $header = "{$name}={$cookie}";
            if ($expire) {
                $expires = new \DateTime();
                $expires = $expires->setTimestamp($expire)->format(\DateTimeInterface::RFC7231);
                $header .= ' Expires='.$expires;
            }
            $this->header('Set-Cookie', $header);
        }

        $body = $this->parseBody();

        return implode("\r\n", [
            "HTTP/1.1 {$this->status_code} {$status}",
            ...array_map(fn ($key) => $key.': '.$this->headers[$key], array_keys($this->headers)),
            '',
            $body,
        ]);
    }

    /**
     * Sends response data back to user.
     */
    public function send(Application $app): static
    {
        $body = $this->parseBody();
        $this->handleHeaders();
        $this->handleStatusCode();
        $this->handleCookies($app->get(CookieJar::class)->cookies());
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

    public function cookie(string $name, string $value, int $expires = 0): static
    {
        $this->cookies[] = [$name, $value, $expires];

        return $this;
    }

    public function cookies(): array
    {
        return $this->cookies;
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

    public function handleCookies(array $cookies): void
    {
        foreach ([...$this->cookies, ...$cookies] as $cookie) {
            setcookie(...[...$cookie, 'httponly' => false, 'path' => '/']);
        }
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

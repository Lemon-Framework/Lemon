<?php

declare(strict_types=1);

namespace Lemon\Http;

use Closure;

/**
 * Class representing HTTP Response.
 *
 * @param mixed $body
 * @param int   $status_code
 */
class Response
{
    /**
     * List of status code handlers.
     */
    public static $handlers = [];

    /**
     * Response body.
     */
    public $body;

    /**
     * Response status code.
     */
    public $status_code;

    /**
     * Response location.
     */
    public $location;

    /**
     * Response headers.
     */
    public $headers;

    public function __construct($body, int $status_code = 200)
    {
        $this->body = $body;
        $this->status_code = $status_code;
        $this->headers = [];
    }

    /**
     * Sets response location.
     */
    public function redirect(string $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Sets response status code.
     */
    public function raise(int $code)
    {
        $this->status_code = $code;

        return $this;
    }

    /**
     * Sets response header.
     */
    public function header(string $header, string $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    public function headers(array $headers)
    {
        foreach ($headers as $header => $value) {
            $this->headers[$header] = $value;
        }

        return $this;
    }

    /**
     * Displays response parameters.
     */
    public function terminate(): void
    {
        $this->handleStatusCode();
        $this->handleLocation();
        $this->handleHeaders();
        $this->handleBody();
    }

    /**
     * Sets status code handler.
     * TODO ne pls
     */
    public static function handle(int $code, Closure|string $action): void
    {
        self::$handlers[$code] = $action;
    }

    /**
     * Handles response status code.
     */
    private function handleStatusCode(): void
    {
        $code = $this->status_code;
        if (!isset(\ERRORS[$code])) {
            return;
        }

        http_response_code($code);

        if (isset(self::$handlers[$code])) {
            (new Response(self::$handlers[$code]()))->terminate();
        } else {
            status_page($code);
        }

        exit;
    }

    /**
     * Redirects to given location, if set.
     */
    private function handleLocation(): void
    {
        $location = $this->location;

        if ($location) {
            header("Location:{$location}");
        }
    }

    /**
     * Sends set response headers.
     */
    private function handleHeaders(): void
    {
        foreach ($this->headers as $header => $value) {
            header($header.':'.$value);
        }
    }

    /**
     * Displays handled response body.
     */
    private function handleBody(): void
    {
        $body = $this->body;

        if (in_array(gettype($body), ['string', 'integer', 'boolean'])) {
            echo $body;

            return;
        }

        if (is_array($body)) {
            header('Content-type:application/json');
            echo json_encode($body);

            return;
        }

        if (!is_object($body)) {
            return;
        }

        if ($body instanceof Response) {
            $body->terminate();
        }

        if ($body instanceof \Lemon\Templating\Template) {
            $body->render();
        }
    }
}

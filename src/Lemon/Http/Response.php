<?php

namespace Lemon\Http;

/**
 * Class representing HTTP Response
 *
 * @param mixed $body
 * @param int $status_code
 */
class Response
{
    /**
     * List of status code handlers
     */
    public static $handlers = [];

    /**
     * Response body
     */
    public $body;

    /**
     * Response status code
     */
    public $status_code;

    /**
     * Response location
     */
    public $location;

    /**
     * Response headers
     */
    public $headers;


    public function __construct($body, int $status_code=200)
    {
        $this->body = $body;
        $this->status_code = $status_code;
        $this->headers = [];
    }

    /**
     * Sets response location
     */
    public function redirect(String $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Sets response status code
     */
    public function raise(int $code)
    {
        $this->status_code = $code;
        return $this;
    }

    /**
     * Sets response header
     */
    public function header(String $header, String $value)
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
     * Displays response parameters
     */
    public function terminate()
    {
        $this->handleStatusCode();
        $this->handleLocation();
        $this->handleHeaders();
        $this->handleBody();
    }

    /**
     * Handles response status code
     */
    private function handleStatusCode()
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

        exit();
    }

    /**
     * Redirects to given location, if set
     */
    private function handleLocation()
    {
        $location = $this->location;

        if ($location) {
            header("Location:$location");
        }
    }

    /**
     * Sends set response headers
     */
    private function handleHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header($header . ":" . $value);
        }
    }

    /**
     * Displays handled response body
     */
    private function handleBody()
    {
        $body = $this->body;

        if (in_array(gettype($body), ["string", "integer", "boolean"])) {
            echo $body;
            return;
        }

        if (is_array($body)) {
            header("Content-type:application/json");
            echo json_encode($body);
            return;
        }

        if (!is_object($body)) {
            return;
        }

        if ($body instanceof Response) {
            $body->terminate();
        }

        if (get_class($body) == "Lemon\Views\View") {
            echo $body->resolved_template;
        }

        if ($body instanceof \Lemon\Views\View) {
            extract($body->arguments);
            eval($body->compiled_template);
        }
    }

    /**
     *
     * Sets status code handler
     *
     * @param int $code
     * @param Closure|String $action
     *
     * */
    public static function handle(int $code, $action)
    {
        self::$handlers[$code] = $action;
    }
}

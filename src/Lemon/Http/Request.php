<?php

namespace Lemon\Http;

class Request
{
    public $uri;

    /** Request headers */
    public $headers;

    /** Request method */
    public $method;

    /** JSON POST input */
    public $json;

    /** POST input */
    public $input;

    /** GET query */
    public $query;

    /** All input data */
    public $data;

    /** Request body */
    public $body;

    public function __construct($request_data)
    {
        foreach ($request_data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Creates new Request instance from sent request
     *
     * @return self
     */
    public static function make()
    {
        return new self([
            'uri' => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD'],
            'headers' => getallheaders(),
            'input' => $_POST,
            'json' => json_decode(file_get_contents('php://input')),
            'body' => file_get_contents('php://input')
        ]);
    }

    /**
     * Emulates requestwith given data
     *
     * @param string $path
     * @param string $method
     * @return self
     */
    public static function emulate(string $path, string $method)
    {
        return new self([
            'uri' => $path,
            'method' => $method
        ]);
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Returns json value
     *
     * @return mixed
     */
    public function json($key)
    {
        return $this->json[$key];
    }

    /**
     * Returns POST input value
     *
     * @return String
     */
    public function input($key)
    {
        return $this->input[$key];
    }

    /**
     * Returns GET query value
     *
     * @return String
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Returns value from data array
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * Returns value from header
     *
     * @return String
     */
    public function header($name)
    {
        return $this->headers[$name];
    }
}

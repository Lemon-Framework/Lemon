<?php

namespace Lemon\Http;

class Request
{
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

    public function __construct($request_data=null)
    {
        if (!$request_data)
            return $this->buildRequest();

        foreach ($request_data as $key => $value)
            $this->$key = $value;
    }

    private function buildRequest()
    {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->headers = getallheaders();
        $this->input = $_POST;
        $this->json = json_decode(file_get_contents("php://input"), true);
        $this->body = file_get_contents("php://input");
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
    function json($key)
    {
        return $this->json[$key];
    }

    /**
     * Returns POST input value
     *
     * @return String
     */
    function input($key)
    {
        return $this->input[$key];
    }

    /**
     * Returns GET query value
     *
     * @return String
     */
    function query()
    {
        return $this->query;
    }

    /**
     * Returns value from data array
     *
     * @return mixed
     */
    function __get($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key];
        return null;
    }

    /**
     * Returns value from header
     *
     * @return String
     */
    function header($name)
    {
        return $this->headers[$name];
    }
}



<?php

namespace Lemon\Http;

class Request
{
    /**
     *
     * Request headers
     *
     * */
    public $headers;

    /**
     *
     * Request method
     *
     * */
    public $method;

    /**
     *
     * Json sent to server
     *
     * */
    public $json;

    /**
     *
     * POST input
     *
     * */
    public $input;

    /**
     *
     * GET input
     *
     * */
    public $query;

    /**
     *
     * All input data
     *
     * */
    public $data;

    /**
     *
     * Sets all request data
     *
     * @param Array $query
     *
     * */
    function __construct($query)
    {
        $this->headers = getallheaders();   
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->json = json_decode(file_get_contents("php://input"), true);
        $this->input = $_POST;
        $this->data = array_merge($this->input, $query);
        $this->query = $query;
    }
    
    /**
     *
     * Returns json value
     *
     * @return mixed
     *
     * */
    function json($key)
    {
        return $this->json[$key];
    }

    /**
     *
     * Returns value from input
     *
     * @return String
     *
     * */
    function input($key)
    {
        return $this->input[$key];
    }

    /**
     *
     * Returns value from query
     * 
     * @return String
     *
     * */
    function query()
    {
        return $this->query;
    }
    
    /**
     *
     * Returns value from data array
     *
     * @return mixed
     *
     * */
    function __get($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key]; 
        return null;
    }

    /**
     *
     * Returns value from header
     *
     * @return String
     *
     * */
    function header($name)
    {
        return $this->headers[$name];
    }
 
}

?>

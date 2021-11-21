<?php

namespace Lemon\Http;

use Lemon\Http\Request;

class MiddlewareCollection
{
    /**
     * List of all middlewares
     */
    private $middlewares;

    public function __construct($middlewares=[])
    {
        $this->middlewares = $this->parse($middlewares); 
    }

    /**
     * Adds new middleware
     *
     * @param String|Array $middlewares
     */
    public function add($middlewares)
    { 
        $parsed = $this->parse($middlewares);
        $this->middlewares = array_merge($this->middlewares, $parsed); 
        return $this;
    }
    
    /**
     * Parses middleware to array
     * 
     * @param String|Array $middlewares
     * @return Array
     */
    private function parse($middlewares)
    {
        if (is_string($middlewares))
            return explode("|", $middlewares);
        return $middlewares;
    }

    /**
     * Executes given middlewares
     *
     * @param Request $request
     */
    public function terminate(Request $request)
    {
        foreach ($this->middlewares as $middleware)
        {
            $middleware_params = explode(":", $middleware);
            $class = $middleware_params[0];
            $class_methods = get_class_methods($class);

            $middleware = new $class;
            $response = null;

            if (in_array("handle", $class_methods))
                $response = $middleware->handle($request);

            if (isset($middleware_params[1]))
                foreach (array_slice($middleware_params, 1) as $method)
                    $response = $middleware->$method($request);

            if ($response)
                response($response)->terminate();
        }
    } 
}


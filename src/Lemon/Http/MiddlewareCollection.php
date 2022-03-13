<?php

namespace Lemon\Http;

class MiddlewareCollection
{
    /**
     * List of all middlewares.
     */
    private $middlewares;

    public function __construct($middlewares = [])
    {
        $this->middlewares = $this->parse($middlewares);
    }

    /**
     * Adds new middleware.
     *
     * @param array|string $middlewares
     */
    public function add($middlewares)
    {
        $parsed = $this->parse($middlewares);
        array_push($this->middlewares, $parsed);

        return $this;
    }

    /**
     * Executes given middlewares.
     */
    public function terminate(Request $request)
    {
        foreach ($this->middlewares as $middleware) {
            $middleware_params = explode(':', $middleware);
            $class = $middleware_params[0];
            $class_methods = get_class_methods($class);

            $middleware = new $class();
            $response = null;

            if (in_array('handle', $class_methods)) {
                $response = $middleware->handle($request);
            }

            if (isset($middleware_params[1])) {
                foreach (array_slice($middleware_params, 1) as $method) {
                    $response = $middleware->{$method}($request);
                }
            }

            if ($response) {
                response($response)->terminate();
            }
        }
    }

    /**
     * Parses middleware to array.
     *
     * @param array|string $middlewares
     *
     * @return array
     */
    private function parse($middlewares)
    {
        if (is_string($this->middlewares)) {
            return explode('|', $this->middlewares);
        }

        return $middlewares;
    }
}

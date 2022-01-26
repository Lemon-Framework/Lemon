<?php

namespace Lemon\Http\Routing;

use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\MiddlewareCollection;

/**
 * Class representing registered route
 *
 * @param String $path
 * @param Array $methods
 * @param callable $action
 */
class Route 
{
    /**
     * Route name
     */
    public $name;

    /**
     * Route path
     */
    public $path;

    /**
     * List of route middlewares
     */
    public $middlewares;

    /**
     * List of supported route methods
     */
    public $methods;

    /**
     * Route action
     */
    public $action;


    public function __construct(string $path, array $methods,  $action)
    {
        $this->path = trim($path, "/");
        $this->methods = $methods;
        $this->action = $action;
        $this->name = $path ?? "main";
        $this->middlewares = new MiddlewareCollection();
    }
    
    /**
     * Adds new middleware
     * 
     * @param String|Array $middlewares
     */
    public function middleware($middlewares)
    {
        $this->middlewares->add($middlewares);
        return $this;
    }

    /**
     * Sets route name
     *
     * @param String $name
     */
    public function name(String $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets route prefix
     * 
     * @param String $prefix
     */
    public function prefix(String $prefix)
    {
        $this->path = trim($prefix) . "/" . $this->path;
        return $this;
    }

    /**
     * Creates response from route action
     *
     * @param Request $request
     * @param Array $params
     *
     * @return Response
     */
    public function toResponse(Request $request, Array $params)
    {
        $this->middlewares->terminate($request);

        $action = $this->action;
        $param_types = getParamTypes($action);
        $last_param = 1;
        $arguments = [];
        foreach ($param_types as $type)
        {
            if ($type == "Lemon\Http\Request")
            {
                array_push($arguments, $request);
                continue;
            }
            array_push($arguments, $params[$last_param]);
            $last_param++;

        }

        if (is_array($action))
        {
            $controller = new $action[0];
            $action = [$controller, $action[1]];
        }

        return new Response(call_user_func_array($action, $arguments));
    }
}



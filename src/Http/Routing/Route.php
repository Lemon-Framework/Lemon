<?php

use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\Routing\RouteCore;

/**
 * Class representing registered route
 *
 * @param String $path
 * @param Array $methods
 * @param String|Closure|Array $action
 */
class Route extends RouteCore 
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


    public function __construct(String $path, Array $methods, $action)
    {
        $this->path = $path;
        $this->methods = $methods;
        $this->action = $action;
        $this->name = $path ? $path : "main";
        $this->middlewares = [];
    }
    
    /**
     * Adds new middleware
     * 
     * @param String|Array $middleware_param
     */
    public function middleware($middleware_param)
    {
        $middleware_param = is_array($middleware_param) ? $middleware_param : explode("|", $middleware_param);
        $this->middlewares = array_merge($this->middlewares, $middleware_param);
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
        $this->path = trim($prefix . "/" . $this->path, "/");
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
        foreach ($this->middlewares as $middleware_name)
        {
            $middleware_params = explode(":", $middleware_name);
            $middleware = new $middleware_params[0]($request);
            $method = isset($middleware_params[1]) ? $middleware_params[1] : null;
            $middleware_methods = get_class_methods($middleware);
            $request_method = strtolower($request->method);
            if (in_array($request_method, $middleware_methods))
                $middleware->$request_method($request);

            if ($method)
                $middleware->$method($request);
        }
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

?>

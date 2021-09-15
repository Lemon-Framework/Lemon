<?php

namespace Lemon\Http\Routing;

/**
 * Class representing route group
 *
 * @param Array $parameters
 * @param Array $routes
 */
class RouteGroup
{
    /**
     * Group name
     */
    public $name;

    /**
     * Group route prefix
     */
    public $prefix;

    /**
     * Group route middlewares
     */
    public $middlewares;

    /**
     * Group member routes
     */
    public $routes;


    public function __construct(Array $parameters, Array $routes)
    {
        $this->name = $parameters["name"];
        $this->middlewares = isset($parameters["middlewares"]) ? $parameters["middlewares"] : []; 
        $this->routes = $routes;
        $this->prefix = $parameters["prefix"]; 
        $this->resolveNested();
        $this->update();
    }

    /**
     * Resolves nested route groups
     */ 
    public function resolveNested()
    {
        foreach ($this->routes as $pos => $route)
            if (get_class($route) == "Lemon\Http\Routing\RouteGroup")
            {
                unset($this->routes[$pos]);
                $this->routes = array_merge($this->routes, $route->routes);
            }
    }

    /**
     * Updates every group member to given parameters
     */
    public function update()
    {
        foreach ($this->routes as $route)
        {
            $route->name = $this->name . ":" . $route->name;
            $route->middleware($this->middlewares);
            $route->prefix($this->prefix);
        }
    }


}
 

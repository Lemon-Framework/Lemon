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

    /**
     * Positions that will get unset after parsing
     */
    public $unset_positions;

    public function __construct(Array $parameters, Array $routes)
    {
        $this->name = $parameters["name"] ?? "";
        $this->middlewares = $parameters["middlewares"] ?? [];
        $this->prefix = $parameters["prefix"] ?? "/";
        $this->routes = $routes;
        $this->unset_positions = [];
        $this->resolve();
        $this->update();
    }

    /**
     * Resolves nested route groups and arays of routes
     */ 
    private function resolve()
    {
        foreach ($this->routes as $pos => $route)
        {
            if (is_array($route))
                $this->resolveRoute($pos, $route);

            else if ($route instanceof \Lemon\Http\Routing\RouteGroup)
                $this->resolveRoute($pos, $route->routes);
        }

        foreach ($this->unset_positions as $position)
            unset($this->routes[$position]);
    }

    /**
     * Resolves routes that aren't Route instance
     */
    private function resolveRoute($pos, $routes)
    {
        array_push($this->unset_positions, $pos);
        $this->routes = array_merge($this->routes, $routes);
    }

    /**
     * Adds middlewares to each route in group
     *      
     * @param array $middlewares
     * @return self
     */
    public function middlewares(array $middlewares)
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
        $this->update();
        return $this;
    }

    /**
     * Sets route uri prefix to each route in group
     *
     * @param string $prefix
     * @return self
     */
    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        $this->update();
        return $this;
    }

    /**
     * Updates route name to each route in group
     *
     * @param string $name
     * @return self
     */
    public function name(string $name)
    {
        $this->name = $name;
        $this->update();
        return $this;
    }

    /**
     * Updates every group member to given parameters
     */
    public function update()
    {
        foreach ($this->routes as $route)
        {
            if ($this->name != "")
                $route->name = $this->name . ":" . $route->name;
            $route->middleware($this->middlewares);
            $route->prefix($this->prefix);
        }
    }


}

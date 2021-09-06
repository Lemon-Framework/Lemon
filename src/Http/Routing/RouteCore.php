<?php
namespace Lemon\Http\Routing;

use Route;
use Lemon\Http\Routing\RouteGroup;

/**
 *
 * RouteCore
 * =========
 * This class builds array of all routes
 *
 * */
class RouteCore
{
    /**
     * All registered routes
     */
    public static $routes = [];

    /**
     * Creates new route
     *
     * @param String $path
     * @param Array $methods
     * @param Closure|String|Array
     *
     * @return Route
     */
    static function createRoute(String $path, Array $methods, $action)
    {  
        if (gettype($action) == "string")
            $action = explode(":", $action);
        $route = new Route(trim($path, "/"), $methods, $action);
        array_push(self::$routes, $route);
        return $route;
    }

    /**
     * Creates route for method GET
     * 
     * @param String $path
     * @param Closure|String|Array $action
     *
     * @return Route
     */
    public static function get(String $path, $action)
    {
        return self::createRoute($path, ["GET"], $action);
    }

    /**
     * Creates route for method POST
     * 
     * @param String $path
     * @param Closure|String|Array $action
     *
     * @return Route
     */
    public static function post(String $path, $action)
    {
        return self::createRoute($path, ["POST"], $action);
    }

    /**
     * Creates route for every request method
     * 
     * @param String $path
     * @param Closure|String|Array $action
     *
     * @return Route
     */
    public static function any(String $path, $action)
    {
        return self::createRoute($path, ["GET", "POST", "PUT", "HEAD", "DELETE", "PATCH", "OPTIONS"], $action);
    }

    /**
     * Creates route for given request methods
     * 
     * @param String $path
     * @param Array $methods
     * @param Closure|String|Array $action
     *
     * @return Route
     */
    public static function use(String $path, Array $methods, $action)
    {
        return self::createRoute($path, $methods, $action);
    }

    /**
     * Sets given parameters for every route
     *
     * @param Array $parameters
     * @param Array $routes
     *
     * @return RouteGroup
     */
    public static function group(Array $parameters, Array $routes)
    {
        return new RouteGroup($parameters, $routes);
    }

    /**
     * Returns route with given name
     *
     * @param String $name
     *
     * @return Route
     */
    public static function byName(String $name)
    {
        foreach (self::$routes as $route)
            if ($route->name == $name)
                return $route;
    }

    /**
     * Returns all routes
     *
     * @return Array
     */
    public static function all()
    {
        return self::$routes;
    }

    /**
     * Executes routing lifecycle
     */
    public static function execute()
    {
        $dispatcher = new Dispatcher(self::$routes);
        $dispatcher->run();
    }
}

?>

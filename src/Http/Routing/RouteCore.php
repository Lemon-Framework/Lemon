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

    public static $controller_resources = [
        "index" => ["get", "/"],
        "create" => ["get", "/create"],
        "store" => ["get", "/create"],
        "show" => ["get", "/{target}"],
        "edit" => ["get", "/{target}/edit"],
        "update" => ["post", "/{target}"],
        "delete" => ["get", "/{target}/delete"]
    ];

    /**
     * Creates new route
     *
     * @param String $path
     * @param Array $methods
     * @param Closure|String|Array $action
     *
     * @return Route
     */
    static function createRoute(String $path, Array $methods, $action)
    {  
        if (is_string($action))
        {
            $action = explode(":", $action);
            if (!isset($action[1]))
                $action = $action[0];
        }
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

    public static function controller(String $base, String $controller)
    {
        $methods = get_class_methods($controller);
        $routes = [];
        foreach ($methods as $method)
        {
            if (in_array($method, ["get", "post", "put", "head", "delete", "path", "options"]))
                array_push($routes, self::createRoute($base, [strtoupper($method)], [$controller, $method]));
            if (isset(self::$controller_resources[$method]))
            {
                $resource = self::$controller_resources[$method];
                $path = $base . $resource[1];
                $request_method = strtoupper($resource[0]);  
                array_push($routes, self::createRoute($path, [$request_method], [$controller, $method]));
            }
        }   
        
        return $routes;
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



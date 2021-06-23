<?php
namespace Lemon\Http;

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
     *
     * Array of all the routes
     *
     * */
    private static $routes = [];

    /**
     *
     * Registers route 
     *
     * @param String $path
     * @param Callback|String $action
     * @param String $method
     *
     *
     * */
    static function addRoute($path, $action, $method)
    {
        $path = trim($path, "/");
        self::$routes[$path][$method] = $action;
    }

    /**
     *
     * Returns routes array
     *
     * @return Array
     *
     *
     * */
    static function getRoutes()
    {
        return self::$routes;
    }

}

?>

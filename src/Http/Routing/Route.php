<?php

require "RouteCore.php";
require "Dispatcher.php";

use Lemon\Http\Routing\RouteCore;
use Lemon\Http\Routing\Dispatcher;

/**
 *
 * Lemon Routing Interface
 *
 * */
class Route
{

    /**
     *
     * Sets route for method GET
     *
     * @param String $path
     * @param Closure|String $callback|$function_name
     *
     * */
    static function get($path, $action)
    {
        RouteCore::addRoute($path, $action, "GET");
    }

    /**
     *
     * Sets route for method POST
     *
     * @param String $path
     * @param Closure|String $callback|$function_name
     *
     * */
    static function post($path, $action)
    {
        RouteCore::addRoute($path, $action, "POST");
    }


    /**
     *
     * Sets route for methods GET and POST
     *
     * @param String $path
     * @param Closure|String $callback|$function_name
     *
     * */
    static function any($path, $action)
    {
        RouteCore::addRoute($path, $action, "GET");
        RouteCore::addRoute($path, $action, "POST");
    }


    /**
     *
     * Sets route for methods that you set
     *
     * @param Array $methods
     * @param String $path
     * @param Closure|String $callback|$function_name
     *
     * */
    static function use($methods, $path, $action)
    {
        foreach ($methods as $method)
            RouteCore::addRoute($path, $action, $method);
    }

    /**
     *
     * Executes dedicated callback
     *
     * */
    static function execute()
    {
        $routes = RouteCore::getRoutes();
        $dispatcher = new Dispatcher($routes);
        $dispatcher->run();
    }
}

?>

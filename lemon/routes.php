<?php

class Route
{
    private $routes = [];
    private $handlers = [];

    /*

        Makes route with only method get

     */
    static function get($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET"]];
    }

    /*

        Makes route with only method post

     */
    static function post($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["POST"]];
    }

    /*

        Makes route with methods get and post

     */
    static function any($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET", "POST"]];
    }
    
    /*

        Makes error handler

     */
    static function handler($error, $action)
    {
        global $handlers;

        $handlers[$error] = $action;

    }

    /*

        Runs whole application

     */
    static function execute()
    {
        global $routes;
        global $handlers;
        $path = $_SERVER['REQUEST_URI'];
        $path = trim($path, "/");
        $params = [];
        $callback = null;

        foreach ($routes as $route => $handler)
        {
            if (preg_match("%^{$route}$%", $path, $matches) === 1)
            {
                $callback = $handler[0];
                $methods = $handler[1];
                unset($matches[0]);
                $params = $matches;
                break;
            }
        }

        if (!$callback || !is_callable($callback))
        {
            raise(404);
            exit;
        }

        if (in_array($_SERVER["REQUEST_METHOD"], $methods))
        {
            call_user_func($callback, ...$params);
        }
        else
        {
            raise(400);
        }

    }
}


?>

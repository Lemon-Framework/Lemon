<?php

class Route
{
    private $routes = [];
    private $handlers = [];

    static function get($path, $action) 
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET"]];
    }
    
    static function post($path, $action) 
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["POST"]];
    }

    static function any($path, $action) 
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET", "POST"]];
    }

    static function handler($error, $action)
    {
        global $handlers;
        
        $handlers[$error] = $action;

    }

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
            if (isset($handlers["404"]))
            {
                $handlers["404"]();

            }
            else
            {
                echo "<h1>404-Not Found</h1> <hr>";
                echo "<h3>Brush</h3>";
            }
            
            http_response_code(404);

            exit;
        }
        
        if (in_array($_SERVER["REQUEST_METHOD"], $methods))
        {
            call_user_func($callback, ...$params);
        }
        else
        {       
            if (isset($handlers["400"]))
            {
                $handlers["400"]();
            }
            else
            {
                echo "<h1>400 Bad Request</h1> <hr>";
                echo "<h3>Brush</h3>";
            }
            
            http_response_code(400);

        }
        
    }
}


?>

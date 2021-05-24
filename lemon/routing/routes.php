<?php
/*
 * 
 * Lemon routing system
 *
 * */
namespace Lemon\Routing;

/*
 *
 * Class to create routes
 *
 * */
class Route
{
    private $routes = [];
    private $handlers = [];
    
    /*
     *
     * Makes route with request only method get
     *
     * @param string $path
     * @param callback $action
     *
     * */
    static function get($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET"]];
    }
    
    /*
     *
     * Makes route with only method post
     *
     * @param string $path
     * @param callback $action
     *
     * */    
    static function post($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["POST"]];
    }

    /*
     *
     * Makes route with only methods get and post
     *
     * @param string $path
     * @param callback $action
     *
     * */
    static function any($path, $action)
    {
        global $routes;
        $path = trim($path, "/");

        $routes[$path] = [$action, ["GET", "POST"]];
    }
    
    /*
     *
     * Makes error handler
     *
     * @param int $error_code
     * @param callback $action
     *
     * */
    static function handler($error, $action)
    {
        global $handlers;

        $handlers[$error] = $action;

    }
    
    /*
     *
     * Executes route that user visited. 
     * Must be on end of every app.
     * 
     * */
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
        if (!$callback)
        {
            raise(404);
            exit;
        }

        if (in_array($_SERVER["REQUEST_METHOD"], $methods))
        {
            try
            {
                $callback(...$params);
            }
            catch(Exception $e)
            {
                raise(500);
                console("Lemon-> Callback error in " . $path . "\nError:" . $e->getMessage());
            }
        }
        else
        {
            raise(400);
        }

    }
    /*
     *
     * Returns all routes with actions
     *
     * */
    static function getRoutes()
    {
        global $routes;
        return $routes;
    }

    static function registerRoutes($new_routes)
    {
        global $routes;
        $routes = array_merge($routes, $new_routes);
    }
}

?>

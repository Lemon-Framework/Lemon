<?php

namespace Lemon\Http\Routing;

require "Helpers.php";

use Lemon\Http\Request;
use Lemon\Http\Response;

/**
 * Class for executing Lemon Lifecycle
 *
 * @param Array $routes
 */
class Dispatcher
{
    /**
     * Request path
     */
    private $request_uri;

    /**
     * List of all registered routes
     */
    private $routes;


    public function __construct(Array $routes)
    {
        $this->request_uri = trim($_SERVER["REQUEST_URI"], "/");
        $this->routes = $routes;
    }

    /**
     * Parses get arguments to array
     *
     * @return Array
     */
    private function parseGet()
    {
        if (preg_match("/\\?(.+)/", $this->request_uri, $matches) == 1)
        {
            $this->request_uri = str_replace("{$matches[0]}", "", $this->request_uri);
            parse_str($matches[1], $get_args);
        }
        else
            $get_args = [];

        return $get_args;
    }

    /**
     * Finds matching routes
     *
     * @return Array
     */
    private function parseURI()
    {
        $matched_routes = [];
        foreach ($this->routes as $route)
        {
            $path = preg_replace("/{[^}]+}/", "(.+)", $route->path);
            if (preg_match("%^{$path}$%", $this->request_uri, $params) === 1)
            {
                unset($params[0]);
                array_push($matched_routes, [$route, $params]);
            }
        }

        if (!$matched_routes)
            (new Response("", 404))->terminate();

        return $matched_routes;

    }

    /**
     * Builds Request instance for accessing Request data
     *
     * @return Request
     */
    private function buildRequest()
    {
        $get_args = $this->parseGet();

        if (empty($get_args))
            $request = new Request([]);
        else
            $request = new Request($get_args);

       return $request;

    }

    /**
     * Runs whole dispatcher
     */
    public function run()
    {
        $request = $this->buildRequest();
        $matched_routes = $this->parseURI();
        
        foreach ($matched_routes as [$route, $params])
           if (in_array($request->method, $route->methods)) 
               exit($route->toResponse($request, $params)->terminate());
        
        (new Response("", 400))->terminate();

    }
}

?>

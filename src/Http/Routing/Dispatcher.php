<?php

namespace Lemon\Http\Routing;

require "Helpers.php";
require __DIR__."/../Response.php";

use Lemon\Http\Request;
use Lemon\Http\Response;

/**
 *
 * Routing dispatcher finds matching route function
 *
 */
class Dispatcher
{
    // User visited route
    private $request_uri;

    // User requested method
    private $request_method;

    // All defined routes
    private $routes;

    /**
     *
     * Setting routing variables
     *
     * @param Array $routes
     *
     */
    function __construct($routes)
    {
        $this->request_uri = trim($_SERVER["REQUEST_URI"], "/");
        $this->request_method = $_SERVER["REQUEST_METHOD"];
        $this->routes = $routes;
    }

    /**
     *
     * Parses get arguments to array
     *
     * @return Array
     *
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
     *
     * Finds matching route
     *
     * @return Array
     *
     *
     */
    private function parseURI()
    {
        foreach ($this->routes as $route => $handler)
        {
            $route = preg_replace("/{[^}]+}/", "(.+)", $route);
            if (preg_match("%^{$route}$%", $this->request_uri, $params) === 1)
            {
                unset($params[0]);
                $matched_handler = $handler;
                break;
            }
        }

        if (!isset($matched_handler))
            Response::raise(404);

        $route = ["handler"=>$matched_handler, "params"=>$params];
        return $route;

    }

    /**
     *
     * Builds Request instance for accessing Request data
     *
     * @return Request
     *
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
     * Processes callback return parameter
     *
     * @param Closure $callback
     * @param Array $params
     *
     */
    private function call(Closure $callback, Array $params)
    {
        $result = $callback(...$params);

        if (in_array(gettype($result), ["string", "integer", "boolean"]))
            echo $result;

        if (gettype($result) == "array")
            jsonify($result);
        
        if (gettype($result) != "object")
            return;

        if (get_class($result) == "Lemon\Views\View")
        {
            extract($result->arguments);
            eval($result->compiled_template);
        }

    }

    /**
     *
     * Runs function that matches request method and uri
     *
     */
    public function run()
    {
        $request = $this->buildRequest();
        $route = $this->parseURI();

        $callback = $route["handler"];
        $params = $route["params"];

        if (!isset($callback[$this->request_method]))
            Response::raise(400);
        $callback = $callback[$this->request_method];

        $param_types = getParamTypes($callback);

        if (isset($param_types[0]))
            if ($param_types[0] == "Lemon\Http\Request")
                $params = array_merge([$request], $params);

        $this->call($callback, $params);
    }
}

?>

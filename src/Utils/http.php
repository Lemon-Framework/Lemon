<?php

use Lemon\Http\Response;

/**
 * Redirects user to given uri
 * 
 * @param String $uri
 *
 * @return Response
 */
function redirect(String $uri)
{
    return (new Response(""))->redirect($uri);
}

/**
 * Sets page status code
 *
 * @param int $code
 *
 * @return Response
 */
function raise(int $code)
{
    return (new Response("", $code));
}

/**
 * Returns route with given name
 *
 * @param String $name
 *
 * @return Route
 */
function route(String $name)
{
    return Route::byName($name);
}

/**
 * Redirects user to given route
 *
 * @param String $route_name
 *
 * @return Response
 */
function to_route(String $route_name, Array $dynamic_params=[])
{
    $route_name = $route_name === "/" ? "main" : $route_name;
    if ($route = route($route_name))
    {
        $path = $route->path === "" ? "/" : $route->path;
        foreach ($dynamic_params as $param)
            $path = preg_replace("/{[^}]+}/", $param, $path);
        return redirect("/$path");
    }
}

/**
 * Creates new response 
 *
 * @param mixed $body=""
 *
 * @return Response
 */
function response($body="")
{
    return new Response($body);
}




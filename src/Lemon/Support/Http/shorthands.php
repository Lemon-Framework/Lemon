<?php

use Lemon\Http\Response;

if (!function_exists('redirect')) {
    /**
     * Redirects user to given uri.
     *
     * @return Response
     */
    function redirect(string $uri)
    {
        return (new Response(''))->redirect($uri);
    }
}

if (!function_exists('raise')) {
    /**
     * Sets page status code.
     *
     * @return Response
     */
    function raise(int $code)
    {
        return new Response('', $code);
    }
}

if (!function_exists('route')) {
    /**
     * Returns route with given name.
     *
     * @return Route
     */
    function route(string $name)
    {
        return Route::byName($name);
    }
}

if (!function_exists('to_route')) {
    /**
     * Redirects user to given route.
     *
     * @return Response
     */
    function to_route(string $route_name, array $dynamic_params = [])
    {
        $route_name = '/' == $route_name ? 'main' : $route_name;
        if ($route = route($route_name)) {
            $path = '' == $route->path ? '/' : $route->path;
            foreach ($dynamic_params as $param) {
                $path = preg_replace('/{[^}]+}/', $param, $path);
            }

            return redirect("/{$path}");
        }
    }
}

if (!function_exists('response')) {
    /**
     * Creates new response.
     *
     * @param mixed $body=""
     *
     * @return Response
     */
    function response($body = '')
    {
        return new Response($body);
    }
}

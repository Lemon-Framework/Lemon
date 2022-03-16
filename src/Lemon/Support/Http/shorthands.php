<?php

declare(strict_types=1);

use Lemon\Http\Response;

if (! function_exists('redirect')) {
    /**
     * Redirects user to given uri.
     */
    function redirect(string $uri): Response
    {
        return (new Response(''))->redirect($uri);
    }
}

if (! function_exists('raise')) {
    /**
     * Sets page status code.
     */
    function raise(int $code): Response
    {
        return new Response('', $code);
    }
}

if (! function_exists('route')) {
    /**
     * Returns route with given name.
     */
    function route(string $name): Route
    {
        return Route::byName($name);
    }
}

if (! function_exists('to_route')) {
    /**
     * Redirects user to given route.
     */
    function to_route(string $route_name, array $dynamic_params = []): Response
    {
        $route_name = $route_name === '/' ? 'main' : $route_name;
        if ($route = route($route_name)) {
            $path = $route->path === '' ? '/' : $route->path;
            foreach ($dynamic_params as $param) {
                $path = preg_replace('/{[^}]+}/', $param, $path);
            }

            return redirect("/{$path}");
        }
    }
}

if (! function_exists('response')) {
    /**
     * Creates new response.
     *
     * @param mixed $body=""
     */
    function response(mixed $body = ''): Response
    {
        return new Response($body);
    }
}

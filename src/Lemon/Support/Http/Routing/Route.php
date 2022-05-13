<?php

declare(strict_types=1);

namespace Lemon\Support\Http\Routing;

use Lemon\Http\Request;
use Lemon\Http\Routing\Route as RouteCore;
use Lemon\Http\Routing\RouteGroup;

class Route
{
    /**
     * All registered routes.
     */
    public static $routes = [];

    public static $controller_resources = [
        'index' => ['get', '/'],
        'create' => ['get', '/create'],
        'store' => ['get', '/create'],
        'show' => ['get', '/{target}'],
        'edit' => ['get', '/{target}/edit'],
        'update' => ['post', '/{target}'],
        'delete' => ['get', '/{target}/delete'],
    ];

    /**
     * Creates new route.
     */
    public static function createRoute(string $path, array $methods, callable $action)
    {
        if (is_string($action)) {
            $action = explode(':', $action);
            if (!isset($action[1])) {
                $action = $action[0];
            }
        }
        $route = new RouteCore($path, $methods, $action);
        array_push(self::$routes, $route);

        return $route;
    }

    /**
     * Creates route for method GET.
     */
    public static function get(string $path, callable $action)
    {
        return self::createRoute($path, ['GET'], $action);
    }

    /**
     * Creates route for method POST.
     */
    public static function post(string $path, callable $action)
    {
        return self::createRoute($path, ['POST'], $action);
    }

    /**
     * Creates route for every request method.
     */
    public static function any(string $path, callable $action)
    {
        return self::createRoute($path, ['GET', 'POST', 'PUT', 'HEAD', 'DELETE', 'PATCH', 'OPTIONS'], $action);
    }

    /**
     * Creates route for given request methods.
     */
    public static function use(string $path, array $methods, callable $action)
    {
        return self::createRoute($path, $methods, $action);
    }

    /**
     * Sets given parameters for every route.
     */
    public static function group(array $parameters, array $routes): RouteGroup
    {
        return new RouteGroup($parameters, $routes);
    }

    public static function controller(string $base, string $controller)
    {
        $methods = get_class_methods($controller);
        $routes = [];
        foreach ($methods as $method) {
            if (in_array($method, ['get', 'post', 'put', 'head', 'delete', 'path', 'options'])) {
                array_push($routes, self::createRoute($base, [strtoupper($method)], [$controller, $method]));
            }
            if (isset(self::$controller_resources[$method])) {
                $resource = self::$controller_resources[$method];
                $path = $base.$resource[1];
                $request_method = strtoupper($resource[0]);
                array_push($routes, self::createRoute($path, [$request_method], [$controller, $method]));
            }
        }

        return $routes;
    }

    /**
     * Returns route with given name.
     */
    public static function byName(string $name): ?Route
    {
        foreach (self::$routes as $route) {
            if ($route->name === $name) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Returns all routes.
     */
    public static function all(): array
    {
        return self::$routes;
    }

    /**
     * Executes routing lifecycle.
     */
    public static function execute(): void
    {
    }
}

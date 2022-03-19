<?php

declare(strict_types=1);

namespace Lemon\Http\Routing;

use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Support\Types\Str;

/**
 * Class for executing Lemon Lifecycle.
 *
 * @param array $routes
 */
class Dispatcher
{
    private Request $request;

    /**
     * Request path.
     */
    private string $request_uri;

    /**
     * List of all registered routes.
     */
    private array $routes;

    public function __construct(array $routes, Request $request)
    {
        $this->request = $request;
        $this->request_uri = trim($request->uri, '/');
        $this->routes = $routes;
    }

    /**
     * Finds matching route for request parameters.
     */
    public function dispatch(): Response
    {
        $this->request->setQuery($this->parseGet());
        $routes = $this->dispatchUri();

        if (! $routes) {
            return new Response('', 404);
        }

        foreach ($routes as [$route, $params]) {
            if (in_array(Str::toLower($this->request->method), $route->methods)) {
                return $route->toResponse($this->request, $params);
            }
        }

        return new Response('', 400);
    }

    /**
     * Parses get arguments to array.
     */
    private function parseGet(): array
    {
        $get_args = [];
        if (preg_match('/\\?(.+)$/', $this->request_uri, $matches) === 1) {
            $this->request_uri = str_replace($matches[0], '', $this->request_uri); // kinda wip
            parse_str($matches[1], $get_args);
        }

        return $get_args;
    }

    /**
     * Finds matching routes.
     */
    private function dispatchUri(): array
    {
        $matched_routes = [];
        foreach ($this->routes as $route) {
            $path = preg_replace('/{.*?}/', '([^/]+)', $route->path);
            if (preg_match("%^{$path}$%", $this->request_uri, $params) === 1) {
                unset($params[0]);
                array_push($matched_routes, [$route, $params]);
            }
        }

        return $matched_routes;
    }
}

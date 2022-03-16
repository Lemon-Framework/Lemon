<?php

declare(strict_types=1);

namespace Lemon\Http\Routing;

use Lemon\Http\MiddlewareCollection;
use Lemon\Http\Request;
use Lemon\Http\Response;

/**
 * Class representing registered route.
 *
 * @param string   $path
 * @param array    $methods
 * @param callable $action
 */
class Route
{
    /**
     * Route name.
     */
    public $name;

    /**
     * Route path.
     */
    public $path;

    /**
     * List of route middlewares.
     */
    public $middlewares;

    /**
     * List of supported route methods.
     */
    public $methods;

    /**
     * Route action.
     */
    public $action;

    public function __construct(string $path, array $methods, $action)
    {
        $this->path = trim($path, '/');
        $this->methods = $methods;
        $this->action = $action;
        $this->name = $path ?? 'main';
        $this->middlewares = new MiddlewareCollection();
    }

    /**
     * Adds new middleware.
     */
    public function middleware(array|string $middlewares)
    {
        $this->middlewares->add($middlewares);

        return $this;
    }

    /**
     * Sets route name.
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets route prefix.
     */
    public function prefix(string $prefix)
    {
        $this->path = trim($prefix).'/'.$this->path;

        return $this;
    }

    /**
     * Creates response from route action.
     */
    public function toResponse(Request $request, array $params): Response
    {
        $this->middlewares->terminate($request);

        $action = $this->action;
        $param_types = getParamTypes($action);
        $last_param = 1;
        $arguments = [];
        foreach ($param_types as $type) {
            if ($type === 'Lemon\\Http\\Request') {
                array_push($arguments, $request);

                continue;
            }
            array_push($arguments, $params[$last_param]);
            ++$last_param;
        }

        if (is_array($action)) {
            $controller = new $action[0]();
            $action = [$controller, $action[1]];
        }

        return new Response(call_user_func_array($action, $arguments));
    }
}

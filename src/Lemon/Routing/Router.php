<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Exception;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Kernel\Container;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;
use Lemon\Templating\Factory as TemplateFactory;

/**
 * The Lemon Router.
 *
 * @method \Lemon\Http\Routing\Route get(string $path, $action)     Creates route with method get
 * @method \Lemon\Http\Routing\Route post(string $path, $action)    Creates route with method post
 * @method \Lemon\Http\Routing\Route put(string $path, $action)     Creates route with method put
 * @method \Lemon\Http\Routing\Route head(string $path, $action)    Creates route with method head
 * @method \Lemon\Http\Routing\Route delete(string $path, $action)  Creates route with method delete
 * @method \Lemon\Http\Routing\Route path(string $path, $action)    Creates route with method path
 * @method \Lemon\Http\Routing\Route options(string $path, $action) Creates route with method options
 */
class Router
{
    public const REQUEST_METHODS = [
        'get',
        'post',
        'put',
        'head',
        'delete',
        'path',
        'options',
    ];

    private Collection $routes;

    private Container $middlewares;

    public function __construct(
        private Lifecycle $lifecycle,
        private TemplateFactory $templates,
        private ResponseFactory $response
    ) {
        $this->middlewares = new Container();
        $this->routes = new Collection($this->middlewares);
    }

    public function __call($name, $arguments)
    {
        if (!Arr::contains(self::REQUEST_METHODS, $name)) {
            throw new Exception('Call to undefined method '.static::class.'::'.$name.'()');
        }

        return $this->routes->add($arguments[0], $name, $arguments[1]);
    }

    /**
     * Creates new route with every request method.
     */
    public function any(string $path, callable $action): Route
    {
        // TODO performance
        foreach (self::REQUEST_METHODS as $method) {
            $this->routes->add($path, $method, $action);
        }

        return $this->routes->find($path);
    }

    public function view(string $path, ?string $view = null)
    {
        $view = $view ?? Str::replace($view, '/', '.'); // @CoolFido

        return $this->routes->add($path, 'get', fn () => $this->templates->make($view));
    }

    public function collection(callable $routes): Collection
    {
        $original = $this->routes;
        $this->routes = new Collection($this->middlewares);
        $routes();
        $collection = $this->routes;
        $this->routes = $original;
        $this->routes->collection($collection);

        return $collection;
    }

    public function file(string $file): Collection
    {
        return $this->collection(function() use ($file){
            require $this->lifecycle->file($file, 'php');
        });
    }

    /**
     * Finds route depending on given request.
     */
    public function dispatch(Request $request): Response
    {
        $result = $this->routes->dispatch($request->path);

        if (!$result) {
            return $this->response->error(404);
        }

        $route = $request[0];
        $action = $route->action($request->method);

        if (!$action) {
            return $this->response->error(400);
        }

        // Middlewares
        foreach ($route->middlewares as $middleware) {
            $response = $this->response->make($middleware);
            if ($response instanceof EmptyResponse) {
                continue;
            }

            return $response;
        }

        return $this->response->make($action, $request[1]);
    }
}

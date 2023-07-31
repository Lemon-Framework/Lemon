<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Contracts\Http\ResponseFactory;
use Lemon\Contracts\Routing\Router as RouterContract;
use Lemon\Contracts\Templating\Factory as TemplateFactory;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Kernel\Application;
use Lemon\Protection\Middlwares\Csrf;
use Lemon\Routing\Attributes\AfterAction;
use Lemon\Routing\Exceptions\RouteException;

/**
 * The Lemon Router.
 */
class Router implements RouterContract
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

    /** @see https://laravel.com/docs/9.x/controllers#actions-handled-by-resource-controller */
    public const CONTROLLER_RESOURCES = [
        'index' => ['get', '/'],
        'create' => ['get', '/create'],
        'store' => ['post', '/create'],
        'show' => ['get', '/{target}'],
        'edit' => ['get', '/{target}/edit'],
        'update' => ['post', '/{target}'],
        'delete' => ['get', '/{target}/delete'],
    ];

    private Collection $routes;

    public function __construct(
        private Application $application,
        private ResponseFactory $response
    ) {
        $this->routes = new Collection();
    }

    /**
     * Creates route for method get.
     */
    public function get(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'get', $action);
    }

    /**
     * Creates route for method post.
     */
    public function post(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'post', $action);
    }

    /**
     * Creates route for method put.
     */
    public function put(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'put', $action);
    }

    /**
     * Creates route for method head.
     */
    public function head(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'head', $action);
    }

    /**
     * Creates route for method delete.
     */
    public function delete(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'delete', $action);
    }

    /**
     * Creates route for method path.
     */
    public function path(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'path', $action);
    }

    /**
     * Creates route for method options.
     */
    public function options(string $path, callable|array $action): Route
    {
        return $this->routes->add($path, 'options', $action);
    }

    /**
     * Creates new route with every request method.
     */
    public function any(string $path, callable|array $action): Route
    {
        foreach (self::REQUEST_METHODS as $method) {
            $this->routes->add($path, $method, $action);
        }

        return $this->routes->find($path);
    }

    /**
     * Creates GET route directly returning view.
     *
     * @author CoolFido sort of
     */
    public function template(string $path, ?string $view = null): Route
    {
        $view = $view ?? str_replace('/', '.', $path);

        return $this->routes->add($path, 'get', fn (TemplateFactory $templates) => $templates->make($view));
    }

    /**
     * Creates GET route that redirects to given url.
     */
    public function redirect(string $path, string $redirect): Route
    {
        return $this->routes()->add($path, 'get', fn() => (new RedirectResponse())->location($redirect));
    }

    /**
     * Creates collection of routes created in given callback.
     */
    public function collection(callable $routes): Collection
    {
        $original = $this->routes;
        $this->routes = new Collection();
        $this->application->call($routes, []);
        $collection = $this->routes;
        $this->routes = $original;
        $this->routes->collection($collection);

        return $collection;
    }

    /**
     * Creates collection of routes created in given file.
     */
    public function file(string $file): Collection
    {
        return $this->collection(function () use ($file) {
            $router = $this;

            require $this->application->file($file, 'php');
        });
    }

    /**
     * Generates collection of given controller.
     */
    public function controller(string $base, string $controller): Collection
    {
        if (!class_exists($controller)) {
            throw new RouteException('Controller '.$controller.' does not exist');
        }

        return $this->collection(function () use ($controller) {
            foreach (get_class_methods($controller) as $method) {
                if (in_array($method, self::REQUEST_METHODS)) {
                    $this->{$method}('/', [$controller, $method]);
                }

                if (isset(self::CONTROLLER_RESOURCES[$method])) {
                    $resource = self::CONTROLLER_RESOURCES[$method];
                    $this->{$resource[0]}($resource[1], [$controller, $method]);
                }
            }
        })->prefix($base);
    }

    /**
     * Creates collection without CSRF protection.
     */
    public function withoutCsrfProtection(callable $routes): Collection
    {
        return $this->collection($routes)->exclude(Csrf::class);
    }

    /**
     * Finds route depending on given request.
     */
    public function dispatch(Request $request): Response
    {
        $result = $this->routes->dispatch(trim($request->path, '/'));

        if (!$result) {
            return $this->response->error(404);
        }

        $route = $result[0];
        $action = $route->action($request->method);

        if (!$action) {
            return $this->response->error(400);
        }

        $after = [];

        foreach ($route->middlewares->resolve() as $middleware) {
            $reflection = new \ReflectionMethod(...$middleware);
            if (!empty($reflection->getAttributes(AfterAction::class))) {
                $after[] = $middleware;

                continue;
            }

            $response = $this->response->make($middleware);
            if ($response instanceof EmptyResponse) {
                continue;
            }

            return $response;
        }

        $prototype = $this->response->make($action, $result[1]);
        $this->application->add(Response::class, $prototype);

        foreach ($after as $middleware) {
            $response = $this->response->make($middleware);
            if ($response instanceof EmptyResponse || $response === $prototype) {
                continue;
            }

            return $response;
        }

        return $prototype;
    }

    /**
     * Returns all routes.
     */
    public function routes(): Collection
    {
        return $this->routes;
    }
}

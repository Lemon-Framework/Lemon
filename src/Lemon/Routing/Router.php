<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Exception;
use Lemon\Http\Request;
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

    public function __construct(
        private TemplateFactory $templates
    ) {
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
        $this->routes = new Collection();
        $routes();
        $collection = $this->routes;
        $this->routes = $original;
        $this->routes->collection($collection);

        return $collection;
    }

    /**
     * Finds route depending on given request.
     */
    public function dispatch(Request $request)
    {
        $this->routes->dispatch($request);
        // When result is null -> 404
        // When result[0] for request method does not exist -> 400
        // otherwise make the response from action NEW HTTP COMPONENT
    }
}

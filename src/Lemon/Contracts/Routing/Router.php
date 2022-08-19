<?php

declare(strict_types=1);

namespace Lemon\Contracts\Routing;

use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Routing\Collection;
use Lemon\Routing\Route;

interface Router
{
    /**
     * Creates route for method get.
     */
    public function get(string $path, callable $action): Route;

    /**
     * Creates route for method post.
     */
    public function post(string $path, callable $action): Route;

    /**
     * Creates route for method put.
     */
    public function put(string $path, callable $action): Route;

    /**
     * Creates route for method head.
     */
    public function head(string $path, callable $action): Route;

    /**
     * Creates route for method delete.
     */
    public function delete(string $path, callable $action): Route;

    /**
     * Creates route for method path.
     */
    public function path(string $path, callable $action): Route;

    /**
     * Creates route for method options.
     */
    public function options(string $path, callable $action): Route;

    /**
     * Creates new route with every request method.
     */
    public function any(string $path, callable|array $action): Route;

    /**
     * Creates GET route directly returning view.
     */
    public function template(string $path, ?string $view = null): Route;

    /**
     * Creates collection of routes created in given callback.
     */
    public function collection(callable $routes): Collection;

    /**
     * Creates collection of routes created in given file.
     */
    public function file(string $file): Collection;

    /**
     * Generates collection of given controller.
     */
    public function controller(string $base, string $controller): Collection;

    /**
     * Finds route depending on given request.
     */
    public function dispatch(Request $request): Response;
}

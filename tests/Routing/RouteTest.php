<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Closure;
use Lemon\Routing\Exceptions\RouteException;
use Lemon\Routing\Route;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RouteTest extends TestCase
{
    public function testAction()
    {
        $closure = Closure::fromCallable(fn () => 'foo');
        $route = new Route('/', ['get' => $closure]);

        $this->assertSame($closure, $route->action('get'));
        $this->assertSame($closure, $route->action('GET'));

        $this->assertNull($route->action('post'));

        $route->action('POST', $closure);
        $this->assertSame($closure, $route->action('post'));

        $route->action('DELETE', [FooController::class, 'handle']);
        $this->assertThat($route->action('DELETE'), $this->equalTo([new FooController(), 'handle']));

        $route->action('put', [FooController::class, 'foo']);
        $this->assertThrowable(function() use($route) {
            $route->action('PUT');
        }, RouteException::class);

        $route->action('head', ['AAAAAAAAAAAA', 'foo']);
        $this->assertThrowable(function() use($route) {
            $route->action('head');
        }, RouteException::class);
    }

    public function testBuildRegex()
    {
        $route = new Route('/foo/{something}/bar/{else}', []);
        $this->assertSame('/foo/(?<something>[a-zA-Z_\-0-9]+)/bar/(?<else>[a-zA-Z_\-0-9]+)', $route->buildRegex());
    }

    public function testMatch()
    {
        $route = new Route('foo/bar', []);
        $this->assertEmpty($route->matches('/foo/bar////'));
        $this->assertNull($route->matches('parek'));
    }

    public function testRegexMatch()
    {
        $route = new Route('foo/{something}/bar/{else}', []);
        $this->assertSame(['something' => 'baz', 'else' => 'parek'], $route->matches('/foo/baz/bar/parek/'));
        $this->assertNull($route->matches('/foo/baz/bar/'));

        $route = new Route('foo/{something}/bar/{else}?', []);
        $this->assertSame(['something' => 'baz', 'else' => 'parek'], $route->matches('/foo/baz/bar/parek'));
        $this->assertSame(['something' => 'baz'], $route->matches('/foo/baz/bar/'));
    }
}

class FooController
{
    public function handle()
    {

    }
}

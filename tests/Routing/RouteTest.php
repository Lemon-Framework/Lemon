<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Closure;
use Lemon\Kernel\Container;
use Lemon\Routing\MiddlewareCollection;
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
        $route = new Route('/', ['get' => $closure], new MiddlewareCollection(new Container()));

        $this->assertSame($closure, $route->action('get'));
        $this->assertSame($closure, $route->action('GET'));

        $this->assertNull($route->action('post'));

        $route->action('POST', $closure);
        $this->assertSame($closure, $route->action('post'));
    }

    public function testBuildRegex()
    {
        $route = new Route('/foo/{something}/bar/{else}', [], new MiddlewareCollection(new Container()));
        $this->assertSame('foo/(?<something>[a-zA-Z_\-0-9]+)/bar/(?<else>[a-zA-Z_\-0-9]+)', $route->buildRegex());
    }

    public function testMatch()
    {
        $route = new Route('/foo/bar', [], new MiddlewareCollection(new Container()));
        $this->assertEmpty($route->matches('foo/bar/'));
        $this->assertEmpty($route->matches('/foo/bar///'));
        $this->assertNull($route->matches('parek'));
    }

    public function testRegexMatch()
    {
        $route = new Route('/foo/{something}/bar/{else}', [], new MiddlewareCollection(new Container()));
        $this->assertSame(['something' => 'baz', 'else' => 'parek'], $route->matches('foo/baz/bar/parek'));
        $this->assertNull($route->matches('foo/baz/bar/'));

        $route = new Route('/foo/{something}/bar/{else}?', [], new MiddlewareCollection(new Container()));
        $this->assertSame(['something' => 'baz', 'else' => 'parek'], $route->matches('foo/baz/bar/parek'));
        $this->assertSame(['something' => 'baz'], $route->matches('foo/baz/bar/'));
    }
}

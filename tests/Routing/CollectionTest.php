<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Lemon\Protection\Middlwares\Csrf;
use Lemon\Routing\Collection;
use Lemon\Routing\Exceptions\RouteException;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CollectionTest extends TestCase
{
    public function testAdding()
    {
        $collection = new Collection();
        $route = $collection->add('/rizek/', 'get', fn () => 'foo');
        $this->assertSame([
            'rizek' => $route,
        ], $collection->routes());

        $collection->add('/rizek', 'post', fn () => 'foo');
        $this->assertSame([
            'rizek' => $route,
        ], $collection->routes());
    }

    public function testFinding()
    {
        $collection = new Collection();
        $route = $collection->add('/rizek', 'get', fn () => 'foo');
        $this->assertSame($route, $collection->find('rizek'));
        $this->expectException(RouteException::class);
        $collection->find('chleba');
    }

    public function testHas()
    {
        $collection = new Collection();
        $collection->add('/rizek', 'get', fn () => 'foo');
        $this->assertTrue($collection->has('rizek'));
        $this->assertFalse($collection->has('nevim'));
    }

    public function testPrefix()
    {
        $collection = new Collection();
        $collection->prefix('/api/');
        $this->assertSame('api', $collection->prefix());
    }

    public function testDispatching()
    {
        $collection = new Collection();
        $collection->add('/', 'get', fn () => 'hello');
        $route = $collection->add('/foo', 'get', fn () => 'hi');

        $dynamic = $collection->add('users/{user}', 'get', fn ($user) => $user);

        $this->assertSame([$route, []], $collection->dispatch('foo'));
        $this->assertSame([$dynamic, ['user' => 'majkel']], $collection->dispatch('users/majkel'));
    }

    public function testRecursiveDispatching()
    {
        $collection = new Collection();
        $collection->add('/', 'get', fn () => 'hello');
        $collection->add('/foo', 'get', fn () => 'hi');

        $collection->add('users/{user}', 'get', fn ($user) => $user);
        $collection->collection($inner = new Collection());
        $route = $inner->add('/bar', 'get', fn () => 'bar');

        $this->assertSame([$route, []], $collection->dispatch('bar'));

        $inner->collection($inner = new Collection());
        $route = $inner->add('posts/{slug}', 'get', fn ($post) => $post);

        $this->assertSame([$route, ['slug' => 'foo']], $collection->dispatch('posts/foo'));
    }

    public function testPrefixDispatching()
    {
        $collection = new Collection();
        $collection->prefix('api');
        $route = $collection->add('foo', 'get', fn () => 'foo');
        $index = $collection->add('/', 'get', fn () => 'bar');
        $this->assertNull($collection->dispatch('foo'));
        $this->assertSame([$route, []], $collection->dispatch('api/foo'));
        $this->assertSame([$index, []], $collection->dispatch('api'));
    }

    public function testMiddlewares()
    {
        $c = new Collection();

        $c->middleware(Csrf::class);

        $c->add('/', 'get', fn () => 'foo');

        $this->assertSame([Csrf::class], $c->dispatch('')[0]->middlewares->middlewares());

        $r = new Collection();
        $r->add('/foo', 'get', fn () => 'foo');
        $c->collection($r);

        $this->assertSame([Csrf::class], $c->dispatch('foo')[0]->middlewares->middlewares());
    }

    public function testExclude()
    {
        $c = new Collection();

        $c->middleware(Csrf::class);

        $c->add('/', 'get', fn () => 'foo');

        $r = new Collection();
        $r->add('/foo', 'get', fn () => 'foo');
        $c->collection($r);
        $r->exclude(Csrf::class);

        $this->assertSame([], $c->dispatch('foo')[0]->middlewares->middlewares());
    }
}

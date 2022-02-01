<?php

namespace Lemon\Tests\Http\Routing;

use Lemon\Http\Request;
use Lemon\Http\Routing\Router;
use Lemon\Kernel\Lifecycle;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testBasicRouting()
    {
        $router = $this->makeRouter();

        $router->get('/', fn() => 'foo');
        $this->assertSame('foo', $router->dispatch(Request::emulate('/', 'get'))->body);

        $router->post('/foo', fn() => 'bar');
        $this->assertSame('bar', $router->dispatch(Request::emulate('/foo', 'post'))->body);
        $this->assertSame('bar', $router->dispatch(Request::emulate('/foo', 'POST'))->body);

        $this->assertSame(404, $router->dispatch(Request::emulate('/baz', 'get'))->status_code);

        $this->assertSame(400, $router->dispatch(Request::emulate('/foo', 'get'))->status_code);

        foreach ($router->request_methods as $method)
            $router->$method('/bar', fn() => 'bar ' . $method);

        foreach ($router->request_methods as $method)
            $this->assertSame('bar ' . $method, $router->dispatch(Request::emulate('/bar', $method))->body);

        $router->any('/baz', fn() => 'baz');

        foreach ($router->request_methods as $method)
            $this->assertSame('baz', $router->dispatch(Request::emulate('/baz', $method))->body);

    }

    public function testGetArgumentsParsing()
    {
        $router = $this->makeRouter();
        
        $router->get('/', fn() => 'foo');

        $this->assertSame('foo', $router->dispatch(Request::emulate('/?bar=baz', 'get'))->body);

        $router->post('/foo', fn() => 'bar');

        $this->assertSame('bar', $router->dispatch(Request::emulate('/foo?baz=bar', 'post'))->body);
    }

    public function makeRouter()
    {
        $lifecycle = new Lifecycle(__DIR__);
        $router = new Router($lifecycle);
        return $router;
    }
}

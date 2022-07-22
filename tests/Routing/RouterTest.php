<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Container;
use Lemon\Kernel\Lifecycle;
use Lemon\Routing\Collection;
use Lemon\Routing\Router;
use Lemon\Support\Filesystem;
use Lemon\Templating\Compiler;
use Lemon\Templating\Factory;
use Lemon\Templating\Template;
use Lemon\Tests\TestCase;
use ReflectionClass;

/**
 * @internal
 * @coversNothing
 */
class RouterTest extends TestCase
{
    public function getRouter(): Router
    {
        $l = new Lifecycle(__DIR__);
        $c = new Config($l);
        $f = new Factory($c, new SimpleCompiler(), $l);
        $r = new Router($l, new ResponseFactory($f, $l));

        $l->add(Router::class, $r);

        return $r;
    }

    public function emulate(string $path, string $method): Request
    {
        return new Request($path, '', $method, [], '', '');
    }

    public function testAdding()
    {
        $r = $this->getRouter();

        $c = new Collection(new Container());

        foreach (Router::REQUEST_METHODS as $method) {
            $c->add('/', $method, fn () => 'idk');
            $r->{$method}('/', fn () => 'idk');
        }

        $this->assertThat($c, $this->equalTo($r->routes()));

        $r = $this->getRouter();
        $r->any('/', fn () => 'idk');
        $this->assertThat($c, $this->equalTo($r->routes()));
    }

    public function testCollection()
    {
        $r = $this->getRouter();

        $r->collection(function (Router $router) {
            $router->get('/', fn () => 'hi');
        });

        $c = new Collection(new Container());
        $i = new Collection(new Container());
        $i->add('/', 'get', fn () => 'hi');
        $c->collection($i);

        $this->assertThat($r->routes(), $this->equalTo($c));
    }

    public function testFile()
    {
        $r = $this->getRouter();

        $r->file('routes.web');

        $c = new Collection(new Container());
        $c->add('/', 'get', fn () => 'hi');
        $c->add('/foo', 'post', fn () => 'foo');
        $this->assertThat($r->routes(), $this->equalTo((new Collection(new Container()))->collection($c)));
    }

    public function testDispatching()
    {
        $r = $this->getRouter();

        $r->get('/', fn () => 'foo');

        $this->assertThat($r->dispatch($this->emulate('/', 'get')), $this->equalTo(new HtmlResponse('foo')));

        $path = Filesystem::join(dirname((new ReflectionClass(ResponseFactory::class))->getFileName()), 'templates', 'error.phtml');
        $this->assertThat($r->dispatch($this->emulate('foo', 'get')), $this->equalTo(new TemplateResponse(new Template($path, $path, ['code' => 404]), 404)));
        $this->assertThat($r->dispatch($this->emulate('/', 'post')), $this->equalTo(new TemplateResponse(new Template($path, $path, ['code' => 400]), 400)));
    }
}

class SimpleCompiler implements Compiler
{
    public function compile(string $template): string
    {
        return $template;
    }

    public function getExtension(): string
    {
        return 'phtml';
    }
}

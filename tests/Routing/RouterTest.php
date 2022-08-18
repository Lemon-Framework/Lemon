<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Application;
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
        $l = new Application(__DIR__);
        $c = new Config($l);
        $f = new Factory($c, new SimpleCompiler(), $l);
        $r = new Router($l, new ResponseFactory($f, $l));

        $l->add(Router::class, $r);

        return $r;
    }

    public function emulate(string $path, string $method): Request
    {
        return new Request($path, '', $method, [], '', []);
    }

    public function testAdding()
    {
        $r = $this->getRouter();

        $c = new Collection();

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

        $c = new Collection();
        $i = new Collection();
        $i->add('/', 'get', fn () => 'hi');
        $c->collection($i);

        $this->assertThat($r->routes(), $this->equalTo($c));
    }

    public function testFile()
    {
        $r = $this->getRouter();

        $r->file('routes.web');

        $c = new Collection();
        $c->add('/', 'get', fn () => 'hi');
        $c->add('/foo', 'post', fn () => 'foo');
        $this->assertThat($r->routes(), $this->equalTo((new Collection())->collection($c)));
    }

    public function testController()
    {
        $r = $this->getRouter();
        $r->controller('/login', Controller::class);

        $c = new Collection();
        $c->add('/', 'get', [Controller::class, 'get']);
        $c->add('/', 'post', [Controller::class, 'post']);
        $c->prefix('login');
        $expect = new Collection();
        $expect->collection($c);

        $r = $this->getRouter();
        $r->controller('posts', ResourceController::class);

        $c = new Collection();
        $c->add('/create', 'get', [ResourceController::class, 'create']);
        $c->add('/{target}', 'get', [ResourceController::class, 'show']);
        $c->add('/', 'get', [ResourceController::class, 'index']);
        $c->prefix('posts');
        $expect = new Collection();
        $expect->collection($c);
        $this->assertThat($r->routes(), $this->equalTo($expect));

        $r = $this->getRouter();
        $r->controller('mixed', MixedController::class);

        $c = new Collection();
        $c->add('/', 'put', [MixedController::class, 'put']);
        $c->add('/create', 'get', [MixedController::class, 'create']);
        $c->add('/{target}', 'put', [MixedController::class, 'update']);
        $c->add('/', 'post', [MixedController::class, 'post']);
        $c->prefix('mixed');
    }

    public function testDispatching()
    {
        $r = $this->getRouter();

        $r->get('/', fn () => 'foo');

        $r->get('foo/bar', fn () => 'bar');

        $this->assertThat($r->dispatch($this->emulate('/', 'GET')), $this->equalTo(new HtmlResponse('foo')));
        $this->assertThat($r->dispatch($this->emulate('/foo/bar', 'GET')), $this->equalTo(new HtmlResponse('bar')));
        $this->assertThat($r->dispatch($this->emulate('/foo/bar/', 'GET')), $this->equalTo(new HtmlResponse('bar')));

        $path = Filesystem::join(dirname((new ReflectionClass(ResponseFactory::class))->getFileName()), 'templates', 'error.phtml');
        $this->assertThat($r->dispatch($this->emulate('foo', 'GET')), $this->equalTo(new TemplateResponse(new Template($path, $path, ['code' => 404]), 404)));
        $this->assertThat($r->dispatch($this->emulate('/', 'POST')), $this->equalTo(new TemplateResponse(new Template($path, $path, ['code' => 400]), 400)));
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

class ResourceController
{
    public function create()
    {
    }

    public function show()
    {
    }

    public function index()
    {
    }
}

class Controller
{
    public function get()
    {
    }

    public function post()
    {
    }
}

class MixedController
{
    public function put()
    {
    }

    public function create()
    {
    }

    public function update()
    {
    }

    public function post()
    {
    }
}

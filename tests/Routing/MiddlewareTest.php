<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Lifecycle;
use Lemon\Protection\Csrf as ProtectionCsrf;
use Lemon\Protection\Middlwares\Csrf;
use Lemon\Routing\MiddlewareCollection;
use Lemon\Routing\Router;
use Lemon\Templating\Factory;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MiddlewareTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCollection()
    {
        $c = new MiddlewareCollection();

        $c->add(Csrf::class);

        $this->assertSame([
            Csrf::class,
        ], $c->middlewares());

        $this->assertThat($c->resolve(), $this->equalTo([[new Csrf(), 'handle']]));

        $l = new Lifecycle(__DIR__);
        $c = new Config($l);
        $f = new Factory($c, new Compiler($c), $l);
        $r = new Router($l, $rs = new ResponseFactory($f, $l));

        $l->add(Request::class, $re = new Request('/', '', 'GET', [], '', []));
        $l->add(ProtectionCsrf::class);
        $l->add(ResponseFactory::class, $rs);

        $l->add(Router::class, $r);

        $r->get('/', fn () => 'foo')->middleware(Csrf::class);

        $this->assertInstanceOf(HtmlResponse::class, $r->dispatch($re));

        $l->add(Request::class, $re = new Request('/', '', 'POST', [], '', []));

        $this->assertInstanceOf(TemplateResponse::class, $r->dispatch($re));
    }
}

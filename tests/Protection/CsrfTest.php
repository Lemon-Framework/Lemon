<?php

declare(strict_types=1);

namespace Lemon\Tests\Protection;

use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Kernel\Lifecycle;
use Lemon\Protection\Csrf;
use Lemon\Protection\Middlwares\Csrf as MiddlwaresCsrf;
use Lemon\Templating\Factory;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CsrfTest extends TestCase
{
    public function testGetToken()
    {
        $c = new Csrf();
        $this->assertFalse($c->created());
        $token = $c->getToken();

        $this->assertSame($token, $c->getToken());
        $this->assertTrue($c->created());
    }

    public function testMiddleware()
    {
        $m = new MiddlwaresCsrf();

        $c = new Csrf();

        $lc = new Lifecycle(__DIR__);
        $cf = new Config($lc);
        $f = new ResponseFactory(new Factory($cf, new Compiler($cf), $lc), $lc);

        $r = new Request('/', '', 'GET', [], '', []); // Lets say we have regular get request

        $this->assertThat($m->handle($r, $c, $f), $this->equalTo((new EmptyResponse())->cookie('CSRF_TOKEN', $c->getToken()))); // Now user has the token in cookie

        $r = new Request('/', '', 'POST', ['Content-Type' => 'application/x-www-form-urlencoded'], 'CSRF_TOKEN='.$c->getToken(), ['CSRF_TOKEN' => $c->getToken()]);
        $this->assertNull($m->handle($r, $c, $f)); // And since everything is all right

        $r = new Request('/', '', 'POST', ['Content-Type' => 'application/x-www-form-urlencoded'], 'CSRF_TOKEN='.$c->getToken(), []);
        $this->assertSame(400, $m->handle($r, $c, $f)->code()); // But when something is missing

        $r = new Request('/', '', 'POST', [], '', ['CSRF_TOKEN' => $c->getToken()]);
        $this->assertSame(400, $m->handle($r, $c, $f)->code());
    }
}

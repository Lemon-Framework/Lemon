<?php

declare(strict_types=1);

namespace Lemon\Tests\Protection;

use Lemon\Config\Config;
use Lemon\Http\CookieJar;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Kernel\Application;
use Lemon\Protection\Csrf;
use Lemon\Protection\Middlwares\Csrf as MiddlwaresCsrf;
use Lemon\Templating\Factory;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
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

        $lc = new Application(__DIR__);
        $cf = new Config($lc);
        $f = new ResponseFactory(new Factory($cf, new Compiler($cf), $lc), $lc);

        $r = new Request('/', '', 'GET', [], '', [], [], ''); // Lets say we have regular get request
        $cj = new CookieJar($r);
        $m->handle($r, $c, $f, $cj);
        $this->assertSame([[['CSRF_TOKEN', $c->getToken()], ['expires' => 0, 'SameSite' => 'Strict']]], $cj->cookies()); // Now user has the token in cookie

        $r = new Request('/', '', 'POST', ['Content-Type' => 'application/x-www-form-urlencoded'], 'CSRF_TOKEN='.$c->getToken(), ['CSRF_TOKEN' => $c->getToken()], [], '');
        $cj = new CookieJar($r);
        $m->handle($r, $c, $f, $cj);
        $this->assertSame([[['CSRF_TOKEN', $c->getToken()], ['expires' => 0, 'SameSite' => 'Strict']]], $cj->cookies()); // Now user has new token in cookie

        $r = new Request('/', '', 'POST', ['Content-Type' => 'application/x-www-form-urlencoded'], 'CSRF_TOKEN='.$c->getToken(), [], [], '');
        $cj = new CookieJar($r);
        $this->assertSame(400, $m->handle($r, $c, $f, $cj)->code()); // But when something is missing

        $r = new Request('/', '', 'PUT', ['Content-Type' => 'application/x-www-form-urlencoded'], 'CSRF_TOKEN='.$c->getToken(), [], [], '');
        $cj = new CookieJar($r);
        $this->assertSame(400, $m->handle($r, $c, $f, $cj)->code()); // But when something is missing

        $r = new Request('/', '', 'POST', [], '', ['CSRF_TOKEN' => $c->getToken()], [], '');
        $cj = new CookieJar($r);
        $this->assertSame(400, $m->handle($r, $c, $f, $cj)->code());
    }
}

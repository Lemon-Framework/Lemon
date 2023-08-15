<?php

declare(strict_types=1);

namespace Lemon\Tests\Http\Middlewares;

use Lemon\Http\Middlewares\TrimStrings;
use Lemon\Http\Request;
use Lemon\Tests\TestCase;

class TrimStringsTest extends TestCase
{
    public function testTrimStrings()
    {
        $middleware = new TrimStrings();
        $r = new Request('/', '', 'GET', ['Content-Type' => 'application/json'], '{"foo":"     10         "}', [], [], '');     
        $middleware->handle($r);
        $this->assertSame(['foo' => '10'], $r->data());
        $r = new Request('/', 'parek=%20%20rizek&kecup=  horcice', 'GET', ['Content-Type' => 'application/json'], '{"foo":"     10     \n\t\r    "}', [], [], '');     
        $middleware->handle($r);
        $this->assertSame(['foo' => '10'], $r->data());
        $this->assertSame(['parek' => 'rizek', 'kecup' => 'horcice'], $r->query());
    }
}

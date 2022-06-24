<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use Lemon\Http\Request;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RequestTest extends TestCase
{
    public function testTrimQuery()
    {
        $this->assertSame(['foo/bar/', 'name=fido&parek'], Request::trimQuery('foo/bar/?name=fido&parek'));
        $this->assertSame(['foo/bar/', ''], Request::trimQuery('foo/bar/'));
    }

    public function testParseHeaders()
    {
        $this->assertSame(['Content-Type' => 'text/html', 'parek' => 'rizek'], Request::parseHeaders(['Content-Type: text/html', 'parek: rizek']));
        $this->assertEmpty(Request::parseHeaders([]));
    }

    public function testHeaders()
    {
        $r = new Request('/', '', 'get', ['foo' => 'bar'], '');
        $this->assertTrue($r->hasHeader('foo'));
        $this->assertFalse($r->hasHeader('parkovar'));
        $this->assertSame('bar', $r->header('foo'));
        $this->assertNull($r->header('rizkochleboparek'));
        $this->assertSame(['foo' => 'bar'], $r->headers());
    }

    public function testIs()
    {
        $r = new Request('/', '', 'get', ['Content-Type' => 'text/html'], '');
        $this->assertTrue($r->is('text/html'));
        $this->assertFalse($r->is('KLOBASNIK'));
        $r = new Request('/', '', 'get', [], '');
        $this->assertFalse($r->is('nevim'));
    }

    public function testData()
    {
        $r = new Request('/', '', 'get', ['Content-Type' => 'application/json'], '{"foo":"bar"}');
        $this->assertSame(['foo' => 'bar'], $r->data());
        $r = new Request('/', '', 'get', ['Content-Type' => 'application/x-www-form-urlencoded'], 'foo=bar');
        $this->assertSame(['foo' => 'bar'], $r->data());

        $r = new Request('/', '', 'get', ['Content-Type' => 'parek'], 'foo:bar,parek:rizek');
        $r->addParser('parek', fn ($data) => explode(',', $data));
        $this->assertSame(['foo:bar', 'parek:rizek'], $r->data());
    }

    public function testQuery()
    {
        $r = new Request('/', 'parek=rizek&nevim=neco', 'get', [], '');
        $this->assertSame('rizek', $r->query('parek'));
        $this->assertSame(['parek' => 'rizek', 'nevim' => 'neco'], $r->query());
    }
}

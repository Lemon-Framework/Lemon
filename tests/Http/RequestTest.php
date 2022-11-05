<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use Lemon\Http\File;
use Lemon\Http\Request;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RequestTest extends TestCase
{
    public function testTrimQuery()
    {
        $this->assertSame(['foo/bar/', 'name=fido&parek'], Request::trimQuery('foo/bar/?name=fido&parek'));
        $this->assertSame(['foo/bar/', ''], Request::trimQuery('foo/bar/'));
    }

    public function testHeaders()
    {
        $r = new Request('/', '', 'GET', ['foo' => 'bar'], '', [], [], "");
        $this->assertTrue($r->hasHeader('foo'));
        $this->assertFalse($r->hasHeader('parkovar'));
        $this->assertSame('bar', $r->header('foo'));
        $this->assertNull($r->header('rizkochleboparek'));
        $this->assertSame(['foo' => 'bar'], $r->headers());
    }

    public function testIs()
    {
        $r = new Request('/', '', 'GET', ['Content-Type' => 'text/html'], '', [], [], "");
        $this->assertTrue($r->is('text/html'));
        $this->assertFalse($r->is('KLOBASNIK'));
        $r = new Request('/', '', 'GET', [], '', [], [], "");
        $this->assertFalse($r->is('nevim'));
    }

    public function testData()
    {
        $r = new Request('/', '', 'GET', ['Content-Type' => 'application/json'], '{"foo":"bar"}', [], [], "");
        $this->assertSame(['foo' => 'bar'], $r->data());
        $r = new Request('/', '', 'GET', ['Content-Type' => 'application/x-www-form-urlencoded'], 'foo=bar', [], [], "");
        $this->assertSame(['foo' => 'bar'], $r->data());

        $r = new Request('/', '', 'GET', ['Content-Type' => 'parek'], 'foo:bar,parek:rizek', [], [], "");
        $r->addParser('parek', fn ($data) => explode(',', $data));
        $this->assertSame(['foo:bar', 'parek:rizek'], $r->data());
    }

    public function testQuery()
    {
        $r = new Request('/', 'parek=rizek&nevim=neco', 'GET', [], '', [], [], "");
        $this->assertSame('rizek', $r->query('parek'));
        $this->assertSame(['parek' => 'rizek', 'nevim' => 'neco'], $r->query());
    }

    public function testValidation()
    {
        $r = new Request('/', '', 'GET', ['Content-Type' => 'application/json'], '{"foo":"bar"}', [], [], "");
        $this->assertThrowable(function () use ($r) {
            $r->validate(['foo' => 'max:3']);
        }, \Exception::class);
    }

    public function testCookies()
    {
        $r = new Request('/', '', 'GET', [], '', ['foo' => 'bar'], [], "");
        $this->assertTrue($r->hasCookie('foo'));
        $this->assertFalse($r->hasCookie('parek'));

        $this->assertSame(['foo' => 'bar'], $r->cookies());

        $this->assertSame('bar', $r->GETCookie('foo'));
        $this->assertNull($r->getCookie('parekvrohliku'));
    }

    public function testFiles()
    {
        $r = new Request('/', '', 'get', [], '', [], [
            'foo' => new File('foo.php', 'text/plain', $first = __DIR__.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'foo.php', 0, 3),
        ], '');

        $this->assertTrue($r->hasFile('foo'));
        $this->assertFalse($r->hasFile('bar'));

        $this->assertInstanceOf(File::class, $r->file('foo'));
        $this->assertNull($r->file('bar'));

        $this->assertSame('ok
', $r->file('foo')->read());
        $r->file('foo')->copy($second = __DIR__.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'bar.php');

        $this->assertFileEquals($first, $second);
        unlink($second);
    }
}

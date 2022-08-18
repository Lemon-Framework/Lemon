<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use DateTime;
use DateTimeInterface;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\JsonResponse;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ResponseTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/720#issuecomment-10421092
     */
    public function testHeaders()
    {
        $r = new HtmlResponse('', 200, ['Location' => '/']);
        $r->header('foo', 'bar');
        $this->assertSame('bar', $r->header('foo'));
        $this->assertSame(['Location' => '/', 'foo' => 'bar'], $r->headers());
        $r->handleHeaders();
        if (!function_exists('xdebug_get_headers')) {
            $this->markTestSkipped();

            return;
        }
        $this->assertSame(['Location: /', 'foo: bar'], xdebug_get_headers());
    }

    public function testLocation()
    {
        $r = new HtmlResponse();
        $r->location('foo');
        $this->assertSame(['Location' => 'foo'], $r->headers());
    }

    public function testCode()
    {
        $r = new HtmlResponse();
        $r->code(500);
        $this->assertSame(500, $r->code());
        $r->handleStatusCode();
        $this->assertSame(500, http_response_code());
    }

    public function testStringCasting()
    {
        $r = new HtmlResponse('foo');
        $this->assertSame("HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\nfoo", (string) $r);

        $r = new HtmlResponse('foo');
        $r->cookie('foo', 'bar');
        $this->assertSame("HTTP/1.1 200 OK\r\nSet-Cookie: foo=bar\r\nContent-Type: text/html\r\n\r\nfoo", (string) $r);

        $r = new HtmlResponse('foo');
        $time = time();
        $r->cookie('foo', 'bar', $time + 60);
        $expires = (new DateTime())->setTimestamp($time + 60)->format(DateTimeInterface::RFC7231);
        $this->assertSame("HTTP/1.1 200 OK\r\nSet-Cookie: foo=bar Expires={$expires}\r\nContent-Type: text/html\r\n\r\nfoo", (string) $r);

        $r = new HtmlResponse('foo', 500, ['Foo' => 'Bar']);
        $this->assertSame("HTTP/1.1 500 Internal Server Error\r\nFoo: Bar\r\nContent-Type: text/html\r\n\r\nfoo", (string) $r);

        $r = new JsonResponse(['foo' => 'bar']);
        $this->assertSame("HTTP/1.1 200 OK\r\nContent-Type: application/json\r\n\r\n{\"foo\":\"bar\"}", (string) $r);
    }
}

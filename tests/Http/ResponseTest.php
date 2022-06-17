<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use Lemon\Http\Responses\HtmlResponse;
use Lemon\Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @see https://github.com/sebastianbergmann/phpunit/issues/720#issuecomment-10421092
     */
    public function testHeaders()
    {
        $r = new HtmlResponse('', 200, ['Location' => '/']);
        $r->header('foo', 'bar');
        $this->assertSame('bar', $r->header('foo'));
        $this->assertSame(['Location' => '/', 'foo' => 'bar'], $r->headers());
        $r->handleHeaders();
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
}

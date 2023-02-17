<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use Lemon\Http\CookieJar;
use Lemon\Http\Request;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CookieJarTest extends TestCase
{
    public function getJar(array $cookies = []): CookieJar
    {
        return new CookieJar(new Request('', '', '', [], '', $cookies, [], ''));
    }

    public function testGet()
    {
        $jar = $this->getJar(['foo' => 'bar']);
        $this->assertSame('bar', $jar->get('foo'));
        $this->assertNull($jar->get('parek'));
    }

    public function testSet()
    {
        $jar = $this->getJar();
        $jar->set('foo', 'bar');
        $jar->set('bar', 'baz', 3600);

        $this->assertSame([['foo', 'bar', 0], ['bar', 'baz', 3600]], $jar->cookies());
    }

    public function getDelete()
    {
        $jar = $this->getJar(['foo' => 'bar']);
        $jar->delete('foo');
        $this->assertSame([['foo', '', -1]], $jar->cookies());
        $jar->delete('bar');
        $this->assertSame([['foo', '', -1]], $jar->cookies());
    }

    public function testHas()
    {
        $jar = $this->getJar(['foo' => 'bar']);
        $this->assertTrue($jar->has('foo'));
        $this->assertFalse($jar->has('parek'));
    }
}

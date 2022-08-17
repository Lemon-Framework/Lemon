<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Types;

use Lemon\Support\Types\Just;
use Lemon\Support\Types\Maybe;
use Lemon\Support\Types\Nothing;
use Lemon\Tests\TestCase;

class MaybeTest extends TestCase
{
    public function testJust()
    {
        $this->assertInstanceOf(Just::class, Maybe::just('foo'));
        $this->assertSame('foo', Maybe::just('foo')->unwrap());
        $this->assertSame('foo', Maybe::just('foo')->unwrap('cscs'));
        $this->assertSame('foo', Maybe::just('foo')->expect('bad'));
        $this->assertSame('Foo', Maybe::just('foo')->then('ucfirst')->unwrap());
    }

    public function testNothing()
    {
        $this->assertInstanceOf(Nothing::class, Maybe::nothing());
        $this->assertNull(Maybe::nothing()->unwrap());
        $this->assertSame('foo', Maybe::nothing()->unwrap('foo'));
        $this->assertNull(Maybe::nothing()->then('ucfirst')->unwrap());
        
        $this->expectExceptionMessage('bad');
        Maybe::nothing()->expect('bad');
    }

    public function testMaybe()
    {
        $this->assertSame('bar', $this->get(['foo' => 'bar'], 'foo')->unwrap());
        $this->assertSame('Bar', $this->get(['foo' => 'bar'], 'foo')->then('ucfirst')->unwrap());
        $this->assertSame('bar', $this->get(['foo' => 'bar'], 'foo')->expect('wrong'));
        $this->assertSame('bar', $this->get(['foo' => 'bar'], 'baz')->unwrap('bar'));

        $this->expectExceptionMessage('wrong');
        $this->get(['foo' => 'bar'], 'AAAA')->expect('wrong');
    }

    public function get(array $target, string $key): Maybe
    {
        if (!isset($target[$key])) {
            return Maybe::nothing();
        }

        return Maybe::just($target[$key]);
    }
}

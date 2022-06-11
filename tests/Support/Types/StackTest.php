<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Types;

use InvalidArgumentException;
use Lemon\Support\Types\Stack;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class StackTest extends TestCase
{
    public function testPush()
    {
        $stack = new Stack();
        $stack->push('foo');
        $stack->push(1);
        $stack->push([]);
        $this->assertSame(['foo', 1, []], $stack->storage());

        $stack = Stack::withType('integer');
        $stack->push(1);
        $this->expectException(InvalidArgumentException::class);
        $stack->push('foo');
    }

    public function testPop()
    {
        $stack = new Stack();
        $stack->push('foo');
        $stack->push('bar');
        $this->assertSame('bar', $stack->pop());
        $this->assertSame(['foo'], $stack->storage());
    }

    public function testTop()
    {
        $stack = new Stack();
        $stack->push('foo');
        $stack->push('bar');
        $this->assertSame('bar', $stack->top());
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Types;

use Exception;
use Lemon\Support\Types\Arr;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ArrTest extends TestCase
{
    public function testFromJson()
    {
        $this->assertSame([1, 'foo', ['foo' => 2]], Arr::fromJson('[1,"foo",{"foo":2}]')->content);
        $this->assertEmpty(Arr::fromJson('[]')->content);
    }

    public function testFrom()
    {
        $this->assertSame([1, 2, 3], Arr::from([1, 2, 3])->content);
        $this->assertSame([1, 2, 'foo', ['bar' => 3]], Arr::from([1, 2, 'foo', ['bar' => 3]])->export());
        $this->assertEmpty(Arr::from([])->content);
    }

    public function testOf()
    {
        $this->assertSame([1, 2, 3], Arr::of(1, 2, 3)->content);
        $this->assertSame([1, 2, 'foo', ['bar' => 3]], Arr::of(1, 2, 'foo', ['bar' => 3])->export());
        $this->assertEmpty(Arr::of()->content);
    }

    public function testRange()
    {
        $this->assertSame([1, 2, 3, 4, 5, 6], Arr::range(1, 6)->content);
        $this->assertSame([2, 4, 6, 8, 10], Arr::range(2, 11, 2)->content);
        $this->assertSame([0], Arr::range(0, 0)->content);
        $this->assertSame([5, 4, 3, 2, 1, 0], Arr::range(5, 0)->content);
        $this->assertSame([5, 3, 1], Arr::range(5, 0, 2)->content);
    }

    public function testEmpty()
    {
        $this->assertEmpty(Arr::empty()->content);
    }

    public function testCalling()
    {
        $this->assertSame(10, Arr::get([20, 30, 10, 40], 2));
        $this->expectException(Exception::class);
        Arr::klobasa();
    }
}

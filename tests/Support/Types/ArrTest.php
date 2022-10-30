<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Types;

use Lemon\Support\Types\Arr;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ArrTest extends TestCase
{
    public function testLast()
    {
        $this->assertSame('foo', Arr::last(['klobna', 'parek', 'rizek', 'foo']));
        $this->assertSame('foo', Arr::last(['bar' => 'klobna', 'baz' => 'parek', 'nevim' => 'rizek', 'neco' => 'foo']));
        $this->assertNull(Arr::last([]));
    }

    public function testFirst()
    {
        $this->assertSame('klobna', Arr::first(['klobna', 'parek', 'rizek', 'foo']));
        $this->assertSame('klobna', Arr::first(['bar' => 'klobna', 'baz' => 'parek', 'nevim' => 'rizek', 'neco' => 'foo']));
        $this->assertNull(Arr::first([]));
    }

    public function testRange()
    {
        $this->assertSame([1, 2, 3, 4, 5], iterator_to_array(Arr::range(1, 5)));
        $this->assertSame([5, 4, 3, 2, 1], iterator_to_array(Arr::range(5, 1)));
        $this->assertSame([1], iterator_to_array(Arr::range(1, 1)));
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Types;

use Lemon\Support\Types\Arr;
use Lemon\Tests\TestCase;

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
}

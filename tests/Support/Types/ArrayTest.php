<?php

namespace Lemon\Tests\Types;

use Lemon\Support\Types\Array_;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testIndexing()
    {
        $array = new Array_([1, 2, 3]);
        $this->assertSame(2, $array[1]);
        $this->assertSame(3, $array[-1]);
        $array[1] = 3;
        $this->assertSame([1, 3, 3], $array->content);
        $array[] = 1;
        $this->assertSame([1, 3, 3, 1], $array->content);
        $this->assertTrue(isset($array[3]));
        $this->assertFalse(isset($array[4]));
        unset($array[3]);
        $this->assertSame([1, 3, 3], $array->content);

        $asoc = new Array_(['foo' => 'bar']);
        $this->assertSame('bar', $asoc['foo']);
        $this->assertSame('bar', $asoc->foo);
    }
}

<?php

namespace Lemon\Tests\Types;

use Exception;
use Lemon\Support\Types\Array_;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testIndexing()
    {
        $array = new Array_([1, 2, 3]);
        $this->assertSame(2, $array[1]);
        $this->assertSame(2, $array->get(1));
        $this->assertSame(3, $array[-1]);
        $array[1] = 3;
        $this->assertSame([1, 3, 3], $array->content);
        $array->set(2, 1);
        $this->assertSame([1, 3, 1], $array->content);
        $array[] = 1;
        $this->assertSame([1, 3, 1, 1], $array->content);
        $this->assertTrue(isset($array[3]));
        $this->assertFalse(isset($array[4]));
        unset($array[3]);
        $this->assertSame([1, 3, 1], $array->content);
        unset($array[3]);
        $this->assertSame([1, 3, 1], $array->content);

        $asoc = new Array_(['foo' => 'bar']);
        $this->assertSame('bar', $asoc['foo']);
        $this->assertSame('bar', $asoc->get('foo'));
        $this->assertSame('bar', $asoc->foo);
        $asoc['baz'] = 'foo';
        $this->assertSame(['foo' => 'bar', 'baz' => 'foo'], $asoc->content);
        $asoc->set('baz', 'bar');
        $this->assertSame(['foo' => 'bar', 'baz' => 'bar'], $asoc->content);
        $asoc->baz = 'bar';
        $this->assertSame(['foo' => 'bar', 'baz' => 'bar'], $asoc->content);
        unset($asoc['baz']);
        $this->assertSame(['foo' => 'bar'], $asoc->content);
        $this->assertTrue(isset($asoc['foo']));
        $this->assertFalse(isset($asoc['bramboraky']));
        $this->expectException(Exception::class);
        $asoc['bramboraky'];
        $asoc->bramboraky;
    }

    public function testOffsetSlicing()
    {
        $array = new Array_([1, 2, 3, 4, 5]);
        $this->assertSame([2, 3, 4], $array['1..3']->content);
        $this->assertSame([2, 3, 4, 5], $array['1..']->content);
        $this->assertSame([1, 2, 3, 4, 5], $array->content);
        $this->expectException(Exception::class);
        $array['..1'];       
        $array['..'];       
    }

    public function testPush()
    {
        $array = new Array_();
        $array->push(10);
        $this->assertSame([10], $array->content);
    }

    public function testPop()
    {
        $array = new Array_([20, 10]);
        $this->assertSame(10, $array->pop());
        $this->assertSame([20], $array->content);
        $array = new Array_();
        $this->assertNull($array->pop());
        $this->assertSame([], $array->content);
    }

    public function testLenght()
    {
        $array = new Array_([1, 2, 3, 4, "foo", 0.3, [1, 2, 3]]);
        $this->assertSame(7, $array->lenght());
        $array = new Array_();
        $this->assertSame(0, $array->size());
    }

    public function testJson()
    {
        $array = new Array_([1, 2, 'foo', ['foo' => 1, 'bar' => '"baz"']]);
        $this->assertSame('[1,2,"foo",{"foo":1,"bar":"\"baz\""}]', $array->json());    
        $array = new Array_();
        $this->assertSame('[]', $array->json());
    }

    public function testExport()
    {
        $array = new Array_([1, 'foo', new Array_(['bar' => new Array_(['baz', 37])])]);
        $this->assertSame([1, 'foo', ['bar' => ['baz', 37]]], $array->export());
        $array = new Array_();
        $this->assertSame([], $array->export());
    }

    public function testChunks()
    {
        $array = new Array_([1, 2, 3, 4, 5]);
        $this->assertSame([[1, 2, 3], [4, 5]], $array->chunk(3)->export());
        $this->assertSame([1, 2, 3, 4, 5], $array->content);
        $array = new Array_();
        $this->assertSame([[]], $array->chunk(5)->export());

    }

    public function testHasKey()
    {
        $array = new Array_([1, 2, 3]);
        $this->assertTrue($array->hasKey(2));
        $this->assertFalse($array->hasKey(3));
        $asoc = new Array_(['foo' => 'bar']);
        $this->assertTrue($asoc->hasKey('foo'));
        $this->assertFalse($asoc->hasKey('cisticka_odpadnich_vod'));       
    }

    public function testFilter()
    {
        $array = new Array_([1, 2, 3, 4, 5, 6]);
        $this->assertSame([0 => 1, 2 => 3, 4 => 5], $array->filter(fn($item) => $item % 2)->content);
        $this->assertSame([1, 2, 3, 4, 5, 6], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->filter(fn($item) => $item == 2)->content);
    }

    public function testFirst()
    {
        $array = new Array_(['foo' => 'bar', 'baz' => 'foo']);
        $this->assertSame('foo', $array->firstKey());
        $this->assertSame('bar', $array->first());
        $this->assertSame(['foo' => 'bar', 'baz' => 'foo'], $array->content);
        $array = new Array_([1, 2, 3]);
        $this->assertSame(0, $array->firstKey());
        $this->assertSame(1, $array->first());
        $array = new Array_();
        $this->assertNull($array->firstKey());
        $this->assertNull($array->first());
    }

    public function testLast()
    {
        $array = new Array_(['foo' => 'bar', 'baz' => 'foo']);
        $this->assertSame('baz', $array->lastKey());
        $this->assertSame('foo', $array->last());
        $this->assertSame(['foo' => 'bar', 'baz' => 'foo'], $array->content);
        $array = new Array_([1, 2, 3]);
        $this->assertSame(2, $array->lastKey());
        $this->assertSame(3, $array->last());
        $array = new Array_([1]);
        $this->assertSame(0, $array->lastKey());
        $this->assertSame(1, $array->last());
        $array = new Array_();
        $this->assertNull($array->lastKey());
        $this->assertNull($array->last());
    }

    public function testKeys()
    {
        $array = new Array_(['foo' => 'bar', 'baz' => 'foo']);       
        $this->assertSame(['foo', 'baz'], $array->keys()->content);
        $this->assertSame(['foo' => 'bar', 'baz' => 'foo'], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->keys()->content);
    }

    public function testValues()
    {
        $array = new Array_(['foo' => 'bar', 'baz' => 'foo']);       
        $this->assertSame(['bar', 'foo'], $array->values()->content);
        $this->assertSame(['foo' => 'bar', 'baz' => 'foo'], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->values()->content);
    }

    public function testMap()
    {
        $array = new Array_([1, 2, 3, 4, 5, 6]); 
        $this->assertSame([2, 4, 6, 8, 10, 12], $array->map(fn($item) => $item * 2)->content);
        $this->assertSame([1, 2, 3, 4, 5, 6], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->map(fn($item) => $item * 2)->content);
    }

    public function testMerge()
    {
        $array = new Array_([1]);
        $this->assertSame([1, 2, 3, 4, 5], $array->merge([2, 3], new Array_([4, 5]))->content);
        $this->assertSame([1], $array->content);
    } 

    public function testRandomKey()
    {
        $array = new Array_(['foo' => 1, 2 => 'bar']);
        $this->assertContains($array->randomKey(), $array->keys()->content);
        $this->assertSame(['foo' => 1, 2 => 'bar'], $array->content);
        $array = new Array_();
        $this->assertNull($array->randomKey());
    }

    public function testRandom()
    {
        $array = new Array_([1, 2, 3, 4]);
        $this->assertContains($array->random(), $array->content);
        $this->assertSame([1, 2, 3, 4], $array->content);
        $array = new Array_();
        $this->assertNull($array->random());
    }

    public function testShuffle()
    {
        $array = new Array_([1, 2, 3, 4]);
        $array->shuffle();
        $this->assertSame([1, 2, 3, 4], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->shuffle()->content);
    }

    public function testReplace()
    {
        $array = new Array_([1, 2, 3, 4]);
        $this->assertSame([4, 5, 3, 6], $array->replace([4, 5], new Array_([3 => 6]))->content);
        $this->assertSame([1, 2, 3, 4], $array->content);
        $array = new Array_();
        $this->assertSame([1, 2, 3], $array->replace([1, 2, 3])->content);
    }

    public function testReverse()
    {
        $array = new Array_(['foo', 'bar', 'baz']);
        $this->assertSame(['baz', 'bar', 'foo'], $array->reverse()->content);
        $this->assertSame(['foo', 'bar', 'baz'], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->reverse()->content);
    }

    public function testSlice()
    {
        $array = new Array_([1, 2, 3, 4, 5]);
        $this->assertSame([2, 3], $array->slice(1, 2)->content);
        $this->assertSame([1, 2, 3, 4, 5], $array->content);
        $array = new Array_();
        $this->assertEmpty($array->slice(1, 2)->content);
    }

    public function testSum()
    {
        $array = new Array_([1, 2, 3]);
        $this->assertSame(6, $array->sum());
        $this->assertSame([1, 2, 3], $array->content);
        $array = new Array_(['foo', 2, 3]);
        $this->assertSame(5, $array->sum());
        $this->assertSame(['foo', 2, 3], $array->content);
        $array = new Array_();
        $this->assertSame(0, $array->sum());
    }

    public function testContains() 
    {
        $array = new Array_(['bramboraky', 'parkovar', 37]);
        $this->assertTrue($array->contains('bramboraky'));
        $this->assertFalse($array->contains('parky'));
    }
}

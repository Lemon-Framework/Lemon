<?php

namespace Lemon\Tests\Debug;

use Lemon\Debug\Dumper;
use Lemon\Support\Types\Array_;
use Lemon\Tests\Debug\Fixtures\FooObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DumperTest extends TestCase
{
    public function getDumper()
    {
        return new Dumper();
    }

    public function testTypeResolver()
    {
        $dumper = $this->getDumper();
        $this->assertSame('parseNull', $dumper->resolveType(null));
        $this->assertSame('parseNumber', $dumper->resolveType(-11.5));
        $this->assertSame('parseString', $dumper->resolveType('bramboraky'));
        $this->assertSame('parseIterator', $dumper->resolveType(['foo', 'bar', 'baz']));
        $this->assertSame('parseIterator', $dumper->resolveType(new Array_(['foo, bar, baz'])));
        $this->assertSame('parseObject', $dumper->resolveType(new FooObject()));
    }

    public function testNumericParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-number">-10.5</span>', $dumper->parseNumber(-10.5));
    }

    public function testBoolParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-bool">true</span>', $dumper->parseBool(true));
        $this->assertSame('<span class="ldg-bool">false</span>', $dumper->parseBool(false));
    }

    public function testStringParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-string">"bramboraky"</span>', $dumper->parseString('bramboraky'));
    }

    public function testIteratorParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[0]</span> => <span class="ldg-number">10</span></span></details>]', $dumper->parseIterator([10]));

        // I apologize to eveyone who will try to read it
        $this->assertSame('<details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[0]</span> => <span class="ldg-number">10</span></span><span class="ldg-array-item"><span class="ldg-array-key">[1]</span> => <details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[0]</span> => <span class="ldg-string">"lisky"</span></span></details>]</span></details>]', $dumper->parseIterator([10, ['lisky']]));
    }

}

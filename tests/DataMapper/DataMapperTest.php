<?php

declare(strict_types=1);

namespace Lemon\Tests\DataMapper;

use Lemon\DataMapper\DataMapper;
use Lemon\Http\Request;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class DataMapperTest extends TestCase
{
    public function testMapping(): void
    {
        $data = [
            'foo' => 'bar',
            'bar' => 10,
        ];

        $this->assertThat(DataMapper::mapTo($data, TestObject::class), $this->equalTo(new TestObject('bar', 10)));

        $request = new Request('', '', '', ['Content-Type' => 'application/json'], json_encode($data), [], [], '');
        $this->assertThat($request->mapTo(TestObject::class), $this->equalTo(new TestObject('bar', 10)));

        $data = [
            'foo' => 'bar',
        ];

        $this->assertNull(DataMapper::mapTo($data, TestObject::class));

        $data = [
            'foo' => ['foo', 'bar'],
        ];

        $this->assertNull(DataMapper::mapTo($data, TestObject::class));

        $data = [
            'bar' => 10,
            'baz' => 'asfjals;g',
            'foo' => 'bar',
        ];

        $this->assertThat(DataMapper::mapTo($data, TestObject::class), $this->equalTo(new TestObject('bar', 10)));

        $data = [
            'baz' => 'parek',
            'foo' => [
                'foo' => 'bar',
                'bar' => 10,
            ],
        ];

        $this->assertThat(DataMapper::mapTo($data, TestObjectNested::class), $this->equalTo(new TestObjectNested('parek', new TestObject('bar', 10))));

        $data = [
            'baz' => 'parek',
            'foo' => 'parek',
        ];

        $this->assertNull(DataMapper::mapTo($data, TestObjectNested::class));

        $data = [
            'foo' => ['bar', 'baz'],
        ];

        $this->assertThat(DataMapper::mapTo($data, TestArrayObject::class), $this->equalTo(new TestArrayObject(['bar', 'baz'])));
    }
}

class TestObject
{
    public function __construct(
        public readonly string $foo,
        public readonly int $bar,
    ) {
    }
}

class TestObjectNested
{
    public function __construct(
        public readonly string $baz,
        public readonly TestObject $foo,
    ) {
    }
}

class TestArrayObject
{
    public function __construct(
        public readonly array $foo,
    ) {
    }
}

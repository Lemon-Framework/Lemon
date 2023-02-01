<?php

declare(strict_types=1);

namespace Lemon\Tests\DataMarshaller;

use Lemon\Tests\TestCase;
use Lemon\DataMarshaller\DataMarshaller;

class DataMarshallerTest extends TestCase
{
    public function testConverting()
    {
        $data = [
            'foo' => 'bar',
            'bar' => 10,
        ];

        $this->assertThat(DataMarshaller::convert($data, TestObject::class), $this->equalTo(new TestObject('bar', 10)));

        $data = [
            'foo' => 'bar',
        ];

        $this->assertNull(DataMarshaller::convert($data, TestObject::class));

        $data = [
            'foo' => ['foo', 'bar'],
        ];

        $this->assertNull(DataMarshaller::convert($data, TestObject::class));

        $data = [
            'bar' => 10,
            'baz' => 'asfjals;g',
            'foo' => 'bar',
        ];

        $this->assertThat(DataMarshaller::convert($data, TestObject::class), $this->equalTo(new TestObject('bar', 10)));

        $data = [
            'baz' => 'parek',
            'foo' => [
                'foo' => 'bar',
                'bar' => 10,
            ],
        ];

        $this->assertThat(DataMarshaller::convert($data, TestObjectNested::class), $this->equalTo(new TestObjectNested('parek', new TestObject('bar', 10))));

        $data = [
            'baz' => 'parek',
            'foo' => 'parek',
        ];

        $this->assertNull(DataMarshaller::convert($data, TestObjectNested::class));
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

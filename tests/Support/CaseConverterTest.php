<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Support\CaseConverter;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CaseConverterTest extends TestCase
{
    public function testFromCamelPascal(): void
    {
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::fromCamelPascal('fooBarBaz'));
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::fromCamelPascal('FooBarBaz'));
        $this->assertSame(['foo', 'bar', 'baz', 'z'], CaseConverter::fromCamelPascal('FooBarBazZ'));
        $this->assertNull(CaseConverter::fromCamelPascal('foo-bar-baz'));
        $this->assertNull(CaseConverter::fromCamelPascal('foo_bar_baz'));
        $this->assertNull(CaseConverter::fromCamelPascal('foo'));
    }

    public function testFromSnakeKebab(): void
    {
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::fromSnakeKebab('foo-bar-baz'));
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::fromSnakeKebab('foo_bar_baz'));
        $this->assertSame(['foo'], CaseConverter::fromSnakeKebab('foo'));
    }

    public function testToArray(): void
    {
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::toArray('fooBarBaz'));
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::toArray('FooBarBaz'));
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::toArray('foo-bar-baz'));
        $this->assertSame(['foo', 'bar', 'baz'], CaseConverter::toArray('foo_bar_baz'));
    }

    public function testToCamel(): void
    {
        $this->assertSame('fooBarBaz', CaseConverter::toCamel('foo-bar-baz'));
    }

    public function testToPascal(): void
    {
        $this->assertSame('FooBarBaz', CaseConverter::toPascal('foo-bar-baz'));
    }

    public function testToSnake(): void
    {
        $this->assertSame('foo_bar_baz', CaseConverter::toSnake('fooBarBaz'));
    }

    public function testToKebab(): void
    {
        $this->assertSame('foo-bar-baz', CaseConverter::toKebab('fooBarBaz'));
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Properties;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;
use PHPUnit\Framework\TestCase;

class Foo
{
    use Properties;

    #[Read]
    private string $bar = 'parek';

    private string $foo;

    #[Read]
    private $baz;
}

/**
 * @internal
 *
 * @coversNothing
 */
class PropertiesTest extends TestCase
{
    public function testGet()
    {
        $f = new Foo();
        $this->assertSame('parek', $f->bar);
        $this->assertNull($f->baz);
        $this->expectException(\Exception::class);
        $f->foo;
        $f->klobna;
    }
}

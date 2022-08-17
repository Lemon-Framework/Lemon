<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Support\ClosureSerializer;
use Lemon\Tests\TestCase;

class ClosureSerializerTest extends TestCase
{
    public function testFunctionSerializer()
    {
        $code = ClosureSerializer::serialize(function($foo) {
            foreach ($foo as $bar) {
                if (!$bar) {
                    return 'foo';
                }
            }
            // {
            return 'foo';
        });

        $this->assertSame('function($foo) { foreach ($foo as $bar) { if (!$bar) { return \'foo\'; } }  return \'foo\'; }', $code);
    }

    public function testArrowFunctionSerializer()
    {
        $code = ClosureSerializer::serialize(fn($foo) => array_map(fn($item) => $item + 1, $foo));

        $this->assertSame('fn($foo) => array_map(fn($item) => $item + 1, $foo)', $code);

        /** @phpstan-ignore-next-line */
        $code = ClosureSerializer::serialize(fn($foo) => array_map(fn($item) => $item + 1, $foo)[1], 'foo');

        $this->assertSame('fn($foo) => array_map(fn($item) => $item + 1, $foo)[1]', $code);

        
        $fn = fn($x) => fn($y) => $x + $y;
        $code = ClosureSerializer::serialize($fn);

        $this->assertSame('fn($x) => fn($y) => $x + $y', $code);
    }
}

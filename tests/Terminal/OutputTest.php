<?php

namespace Lemon\Tests\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Terminal\Output;
use Lemon\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class OutputTest extends TestCase
{
    public function testRendering()
    {
        $lifeycle = new Lifecycle(__DIR__);
        $terminal = new Terminal($lifeycle);
        $output = new Output($terminal, 'foo');

        $this->assertSame('foo', $output->resolve());

        $output = new Output($terminal, '<div class="text-red">foo</div>');

        $this->assertSame("\033[31mfoo\033[39m", $output->resolve());
    }
}

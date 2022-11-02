<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\IO\Html;

use Lemon\Terminal\IO\Html\HtmlOutput;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class HtmlOutputTest extends TestCase
{
    public function testCompiling()
    {
        $o = new HtmlOutput();
        $this->assertSame("\033[33mHodne dobre\033[33m\033[0m\033[0m", $o->compile('<div class="text-yellow">   Hodne dobre   </div>'));
    }
}

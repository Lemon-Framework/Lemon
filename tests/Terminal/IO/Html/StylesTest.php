<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\IO\Html;

use DOMDocument;
use Lemon\Terminal\IO\Html\Styles;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class StylesTest extends TestCase
{
    public function testGetStyle()
    {
        $s = new Styles();
        $dom = new DOMDocument();
        $dom->loadHTML('<div class="text-white bg-yellow"></div>');
        $node = $dom->getElementsByTagName('div')[0];
        $this->assertSame([
            "\033[37m\033[43m",
            '',
            '',
        ], $s->getStyle($node));
    }
}

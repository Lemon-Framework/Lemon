<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel;

use Lemon\Kernel\Application;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ApplicationTest extends TestCase
{
    public function testFile()
    {
        $lc = new Application(__DIR__);
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa', $lc->file('views.klobasa'));
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa.juice', $lc->file('views.klobasa', '   .    juice...   '));
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa.juice', $lc->file('.....views......klobasa...', '   .    juice...   '));
    }
}

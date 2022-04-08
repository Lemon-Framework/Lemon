<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel;

use Lemon\Kernel\Lifecycle;
use PHPUnit\Framework\TestCase;

class LifecycleTest extends TestCase
{
    public function testFile()
    {
        $lc = new Lifecycle(__DIR__);
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa', $lc->file('views.klobasa'));
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa.juice', $lc->file('views.klobasa', '   .    juice...   '));
        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'klobasa.juice', $lc->file('.....views......klobasa...', '   .    juice...   '));
    }    
}

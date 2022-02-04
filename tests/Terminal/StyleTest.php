<?php

namespace Lemon\Tests\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Terminal\StyleCollection;
use Lemon\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{

    protected StyleCollection $styles;

    /**
     * @before
     */
    public function beforeEach()
    {
        $lifecycle = new Lifecycle(__DIR__);
        $terminal = new Terminal($lifecycle);
        $this->styles = new StyleCollection($terminal);
    }

    public function testTextHandler()
    {
        $class = $this->styles->handleTextColor(['text-yellow', 'yellow']);
        $this->assertSame(["\033[33m", '<PARENT>'], $class);
    }

    public function testBackgroundHandler()
    {
        $class = $this->styles->handleBackgroundColor(['bg-yellow', 'yellow']);
        $this->assertSame(["\033[43m", '<PARENT>'], $class);
    }

    public function testClassResolving()
    {
        $this->assertSame(['', ''], $this->styles->resolveClass('foo'));
        $this->assertSame(["\033[31m", '<PARENT>'], $this->styles->resolveClass('text-red'));
    }

}

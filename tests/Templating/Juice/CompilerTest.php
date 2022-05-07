<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

class CompilerTest extends TestCase
{
    public function testCompilation()
    {
        $compiler = new Compiler(new Config(new Lifecycle(__DIR__)));
               
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Config\Config;
use Lemon\Kernel\Application;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CompilerTest extends TestCase
{
    public function testCompilation()
    {
        // Basic compilation testing that both parts can work with each other, theya re both tested separatly
        $compiler = new Compiler(new Config(new Application(__DIR__)));
        $this->assertSame(<<<'HTML'
            <ul>
                <?php foreach ($foo as $baz): ?>
                    <li><?php echo $_env->escapeHtml($baz) ?></li>
                <?php endforeach ?>
            </ul>
        HTML, $compiler->compile(<<<'HTML'
            <ul>
                {foreach $foo as $baz }
                    <li>{{ $baz}}</li>
                {/foreach}
            </ul>
        HTML));
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\Directives\SwitchDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SwitchDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php switch ($foo): ?>', $c->compileOpenning('switch', '$foo', []));
        $this->assertSame('<?php switch ($foo): ?>', $c->compileOpenning('switch', '$foo', ['if']));

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('switch', '', []);
        }, CompilerException::class, $c);
    } 

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('switch'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endswitch ?>', $c->compileClosing('switch'));
    }
}

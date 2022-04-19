<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\Directives\IfDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class IfDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php if (1 == $foo): ?>', $c->compileOpenning('if', '1 == $foo', []));
        $this->assertSame('<?php if (1 == $foo): ?>', $c->compileOpenning('if', '1 == $foo', ['switch']));

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('if', '', []);
        }, CompilerException::class, $c);
    } 

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('if'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endif ?>', $c->compileClosing('if'));
    }
}

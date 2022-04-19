<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\Directives\WhileDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class WhileDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php while ($foo): ?>', $c->compileOpenning('while', '$foo', []));
        $this->assertSame('<?php while ($foo): ?>', $c->compileOpenning('while', '$foo', ['switch']));

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('while', '', []);
        }, CompilerException::class, $c);
    } 

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('while'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endwhile ?>', $c->compileClosing('while'));
    }
}

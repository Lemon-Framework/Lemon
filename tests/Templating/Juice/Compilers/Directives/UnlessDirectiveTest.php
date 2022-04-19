<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class UnlessDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php if (!(1 == $foo)): ?>', $c->compileOpenning('unless', '1 == $foo', []));
        $this->assertSame('<?php if (!(1 == $foo)): ?>', $c->compileOpenning('unless', '1 == $foo', ['switch']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('unless', '', []);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('unless'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endif ?>', $c->compileClosing('unless'));
    }
}

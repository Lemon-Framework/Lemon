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
class CaseDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php case 10: ?>', $c->compileOpenning('case', '10', ['if', 'switch']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('case', '', ['switch']);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('case', '10', []);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('case', '10', ['switch', 'if']);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertFalse($c->isClosable('case'));
    }
}

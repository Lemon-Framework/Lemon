<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\Directives\ElseDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ElseDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php else: ?>', $c->compileOpenning('else', '', ['switch', 'if']));

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('else', '$foo', ['if']);
        }, CompilerException::class, $c);

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('else', '', []);
        }, CompilerException::class, $c);

        $this->assertThrowable(function(DirectiveCompiler $c) {
            $c->compileOpenning('else', '', ['if', 'switch']);
        }, CompilerException::class, $c);
    } 

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertFalse($c->isClosable('else'));
    }
}


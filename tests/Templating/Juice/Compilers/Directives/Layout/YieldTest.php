<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class YieldTest extends TestCase
{
    public function testCompileOpenning()
    {
        $d = new DirectiveCompiler();
        $this->assertSame('<?php $_layout->yield(\'foo\') ?>', $d->compileOpenning(new Token(Token::TAG, ['yield', '"foo"'], 1), []));

        $this->assertSame('<?php $_layout->yield(\'foo\') ?>', $d->compileOpenning(new Token(Token::TAG, ['yield', '\'foo\''], 1), []));

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['yield', ''], 1), []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['yield', 'echo'], 1), []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['yield', '"parek" "rizek"'], 1), []);
        }, CompilerException::class, $d);
    }
}

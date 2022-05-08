<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Compilers\Directives\IfDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

class FooDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        return 'foo '.$token->content;
    }
}

class BarDirective
{
    public function compileOpenning(Token $token, array $stack): string
    {
        return 'bar '.$token->content;
    }
}

// TODO switch compilers test to directivecompiler like youknow
/**
 * @internal
 * @coversNothing
 */
class DirectiveCompilerTest extends TestCase
{
    public function testGetDirectiveCompiler()
    {
        $c = new DirectiveCompiler();
        $this->assertInstanceOf(IfDirective::class, $c->getDirectiveCompiler('if'));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->getDirectiveCompiler('foo');
        }, CompilerException::class, $c);
    }

    public function testAddDirectiveCompiler()
    {
        $c = new DirectiveCompiler();
        $c->addDirectiveCompiler('foo', FooDirective::class);

        $this->assertInstanceOf(FooDirective::class, $c->getDirectiveCompiler('foo'));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->addDirectiveCompiler('foo', FooDirective::class);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->addDirectiveCompiler('bar', BarDirective::class);
        }, CompilerException::class, $c);
    }

    public function testHasDirectiveCompiler()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->hasDirectiveCompiler('if'));
        $this->assertFalse($c->hasDirectiveCompiler('foo'));
    }
}

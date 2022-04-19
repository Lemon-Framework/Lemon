<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\ElseIfDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

class ElseIfDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new ElseIfDirective();
        $this->assertSame('elseif ($foo == 10):', $d->compileOpenning('$foo == 10', ['if']));

        $this->assertThrowable(function(ElseIfDirective $d) {
            $d->compileOpenning('', ['if']);
        }, CompilerException::class, $d);

        $this->assertThrowable(function(ElseIfDirective $d) {
            $d->compileOpenning('$foo', []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function(ElseIfDirective $d) {
            $d->compileOpenning('$foo', ['switch']);
        }, CompilerException::class, $d);
    }
}

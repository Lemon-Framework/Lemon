<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\ElseDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ElseDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new ElseDirective();
        $this->assertSame('else:', $d->compileOpenning('', ['if']));

        $this->assertThrowable(function (ElseDirective $d) {
            $d->compileOpenning('foo', ['if']);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (ElseDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (ElseDirective $d) {
            $d->compileOpenning('', ['switch']);
        }, CompilerException::class, $d);
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\CaseDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

class CaseDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new CaseDirective();
        $this->assertSame('case 10:', $d->compileOpenning('10', ['switch']));
        $this->assertThrowable(function($d) {
            $d->compileOpenning('', ['switch']);
        }, CompilerException::class, $d);

        $this->assertThrowable(function($d) {
            $d->compileOpenning('10', ['if']);
        }, CompilerException::class, $d);

        $this->assertThrowable(function($d) {
            $d->compileOpenning('10', []);
        }, CompilerException::class, $d);
    }
}

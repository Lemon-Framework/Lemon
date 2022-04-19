<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\ForDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

class ForDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new ForDirective();
        $this->assertSame('for ($i = 0; $i < 10; $i++):', $d->compileOpenning('$i = 0; $i < 10; $i++', []));
        $this->assertSame('for ($i = 0; $i < 10; $i++):', $d->compileOpenning('$i = 0; $i < 10; $i++', ['if']));

        $this->assertThrowable(function(ForDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);
    }
}

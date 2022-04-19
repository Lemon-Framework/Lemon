<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\IfDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class IfDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new IfDirective();
        $this->assertSame('if ($foo == 10):', $d->compileOpenning('$foo == 10', []));
        $this->assertSame('if ($foo == 10):', $d->compileOpenning('$foo == 10', ['if']));

        $this->assertThrowable(function (IfDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);
    }
}

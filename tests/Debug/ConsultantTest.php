<?php

declare(strict_types=1);

namespace Lemon\Tests\Debug;

use Lemon\Debug\Handling\Consultant;
use Lemon\Templating\Juice\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ConsultantTest extends TestCase
{
    public function testBestMatch()
    {
        $c = new Consultant();

        $this->assertSame('parek', $c->bestMatch(['parek', 'rohlik'], 'parke'));
        $this->assertSame('dd', $c->bestMatch(['dd', 'drakoparek'], 'dp'));
        $this->assertNull($c->bestMatch([], 'parek'));
    }

    public function testFunction()
    {
        $c = new Consultant();

        $this->assertSame(['Did you mean explode?'], $c->giveAdvice('Call to undefined function explod()'));
        $this->assertSame(['Function was propably not loaded. Try checking your loader'], $c->giveAdvice('Call to undefined function AAAAAAAAAAAAAAAAAA()'));
    }

    public function testMethod()
    {
        $c = new Consultant();

        $this->assertSame(['Did you mean compile?'], $c->giveAdvice('Call to undefined method '.Compiler::class.'::compilr()'));
        $this->assertSame([''], $c->giveAdvice('Call to undefined method '.Compiler::class.'::qqqq()'));
    }

    public function testProperty()
    {
        $c = new Consultant();

        $this->assertSame(['Did you mean $directives?'], $c->giveAdvice('Undefined property: '.Compiler::class.'::$direktizep'));
        $this->assertSame([''], $c->giveAdvice('Undefined property: '.Compiler::class.'::$qaopsfjasdj'));
    }
}

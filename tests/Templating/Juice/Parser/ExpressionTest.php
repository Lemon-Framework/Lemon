<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Parser;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\Expression\Number;
use Lemon\Templating\Juice\Operators;
use Lemon\Templating\Juice\Parser\ExpressionParser;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\Syntax;
use Lemon\Tests\TestCase;

class ExpressionTest extends TestCase
{
    private function getParser(string $content): ExpressionParser 
    {
        $lexer = (new Lexer(new Syntax(), $content))->changeContext(Context::Juice);
        $lexer->next();
        return new ExpressionParser($lexer, new Operators());
    }

    public function testBasicExpression(): void 
    {
        $p = $this->getParser('1*2+3');
        $this->assertThat($p->parse(), $this->equalTo( 
            new BinaryOperation( 
                new BinaryOperation( 
                    new Number('1', new Position(1, 1)),
                    '*',
                    new Number('2', new Position(1, 3)),
                    new Position(1, 1),
                ),
                '+',
                new Number('3', new Position(1, 5)),
                new Position(1, 1),
            )
        ));

        $p = $this->getParser('1+2*3');
        $this->assertThat($p->parse(), $this->equalTo( 
            new BinaryOperation( 
                new Number('1', new Position(1, 1)),
                '+',
                new BinaryOperation( 
                    new Number('2', new Position(1, 3)),
                    '*',
                    new Number('3', new Position(1, 5)),
                    new Position(1, 3),
                ),
                new Position(1, 1),
            )
        ));
    }
}

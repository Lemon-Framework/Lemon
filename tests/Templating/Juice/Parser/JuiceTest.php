<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Parser;

use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Nodes\Directives\EachDirective;
use Lemon\Templating\Juice\Nodes\Directives\ElseDirective;
use Lemon\Templating\Juice\Nodes\Directives\ElseIfDirective;
use Lemon\Templating\Juice\Nodes\Directives\IfDirective;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\Expression\StringLiteral as PHPString;
use Lemon\Templating\Juice\Nodes\Expression\Variable;
use Lemon\Templating\Juice\Nodes\Html\Text;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Nodes\Output;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\Nodes\Html\Node;
use Lemon\Templating\Juice\Nodes\Html\StringLiteral;
use Lemon\Templating\Juice\Nodes\Html\Attribute;
use Lemon\Templating\Juice\Directives;
use Lemon\Templating\Juice\Operators;
use Lemon\Templating\Juice\HtmlNodes;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Parser\Parser;
use Lemon\Tests\TestCase;

class JuiceTest extends TestCase
{
    private function getParser(string $content): Parser 
    {
        return new Parser(new Lexer(new Syntax(), $content), new HtmlNodes(), new Operators(), new Directives());
    }

    public function testEach(): void
    {
        $parser = $this->getParser(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    [ each \$foo as \$bar ] 
       <div class="something">[[ \$bar ]]</div>
    [ /each ]
</body>
</html>
HTML);

        $this->assertEquals(
            new NodeList([ 
                new Node('!DOCTYPE', new Position(1, 1), new NodeList([new Attribute('html', new Position(1, 11), null)])),
                new Node('html', new Position(2, 1), new NodeList([new Attribute('lang', new Position(2, 7), new NodeList([new StringLiteral('en', new Position(2, 13))]))]), new NodeList([
                    new Node('body', new Position(3, 1), new NodeList([]), new NodeList([         
                        new EachDirective(
                            new BinaryOperation(new Variable('foo', new Position(4, 12)), 'as', new Variable('bar', new Position(4, 20)), new Position(4, 12)),
                            new NodeList([new Node('div', new Position(5, 8), new NodeList([new Attribute('class', new Position(5, 13), new NodeList([new StringLiteral('something', new Position(5, 20))]))]), 
                                new NodeList([new Output(new Variable('bar', new Position(5, 34)), new Position(5, 31))]) 
                        )]), new Position(4, 5)),
                    ])),         
                ])),
            ]),
            $parser->parse()
        );

       // todo more tsts jhj 
    }

    public function testIf(): void
    {
        $parser = $this->getParser(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    [ if \$im == "everywhere" ] 
        im
    [ elseif \$im == "so" ]
        julia
    [ else ]
        brat
    [ /if ]
</body>
</html>
HTML);

        $this->assertEquals(
            new NodeList([ 
                new Node('!DOCTYPE', new Position(1, 1), new NodeList([new Attribute('html', new Position(1, 11), null)])),
                new Node('html', new Position(2, 1), new NodeList([new Attribute('lang', new Position(2, 7), new NodeList([new StringLiteral('en', new Position(2, 13))]))]), new NodeList([
                    new Node('body', new Position(3, 1), new NodeList([]), new NodeList([         
                        new IfDirective(
                            new BinaryOperation(new Variable('im', new Position(4, 10)), '==', new PHPString(['everywhere'], new Position(4, 17)), new Position(4, 10)), new NodeList([ new Text('im', new Position(5, 9)), new ElseIfDirective(new BinaryOperation(new Variable('im', new Position(6, 14)), '==', new PHPString(['so'], new Position(6, 21)), new Position(6, 14)), new Position(6, 5)), new Text('julia', new Position(7, 9)),
                                new ElseDirective(null, new Position(8, 5)),
                                new Text('brat', new Position(9, 9)),
                            ]), new Position(4, 5)),
                    ])),         
                ])),
            ]),
            $parser->parse()
        );

       // todo more tsts jhj 
    }

}

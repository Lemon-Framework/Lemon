<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Parser;

use Lemon\Templating\Juice\HtmlNodes;
use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Nodes\Html\Attribute;
use Lemon\Templating\Juice\Nodes\Html\Node;
use Lemon\Templating\Juice\Nodes\Html\StringLiteral;
use Lemon\Templating\Juice\Nodes\Html\Text;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Parser\Parser;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\Syntax;
use Lemon\Tests\TestCase;

class HtmlTest extends TestCase
{
    private function getParser(string $content): Parser 
    {
        return new Parser(new Lexer(new Syntax(), $content), new HtmlNodes());
    }

    public function testHtmlParsing(): void
    {
        $parser = $this->getParser(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    <p>foo+bar - baz::foo</p>
</body>
</html>
HTML);

        $this->assertEquals(
            new NodeList([ 
                new Node('!DOCTYPE', new Position(1, 1), new NodeList([new Attribute('html', new Position(1, 11), null)])),
                new Node('html', new Position(2, 1), new NodeList([new Attribute('lang', new Position(2, 7), new NodeList([new StringLiteral('en', new Position(2, 13))]))]), new NodeList([
                    new Node('body', new Position(3, 1), new NodeList([]), new NodeList([
                        new Node('p', new Position(4, 5), new NodeList([]), new NodeList([ 
                            new Text('foo+bar - baz::foo', new Position(4, 8)),
                        ])),            
                    ])),         
                ])),
            ]),
            $parser->parse()
        );

       // todo more tsts jhj 
    }
}

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
                new Node('!DOCTYPE', new NodeList([new Attribute('html', null)])),
                new Node('html', new NodeList([new Attribute('lang', new NodeList([new StringLiteral('en')]))]), new NodeList([
                    new Node('body', new NodeList([]), new NodeList([
                        new Node('p', new NodeList([]), new NodeList([ 
                            new Text('foo+bar - baz::foo'),
                        ])),            
                    ])),         
                ])),
            ]),
            $parser->parse()
        );
    
    }
}

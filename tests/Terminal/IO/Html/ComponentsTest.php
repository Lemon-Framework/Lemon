<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\IO\Html;

use Lemon\Terminal\IO\Html\Components;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ComponentsTest extends TestCase
{
    public function testParsingDiv()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<div>parek v rohliku</div>'));
        $this->assertSame("parek v rohliku\033[0m", $result);

        $result = $components->parseElement($this->getElement('<div><div>parek v rohliku</div></div>'));
        $this->assertSame("parek v rohliku\033[0m\033[0m", $result);

        $result = $components->parseElement($this->getElement('<div class="bg-yellow">parek v rohliku</div>'));
        $this->assertSame("\033[43mparek v rohliku\033[43m\033[0m", $result);

        $result = $components->parseElement($this->getElement('<div class="bg-yellow">parek <div class="bg-red">v</div> rohliku</div>'));
        $this->assertSame("\033[43mparek \033[43m\033[43m\033[41mv\033[41m\033[0m\033[43m\033[43m rohliku\033[43m\033[0m", $result);
    }

    public function testH1()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<h1>parek</h1>'));
        $this->assertSame(<<<HTML
        +-------+
        | parek\033[0m |
        +-------+

        HTML, $result);
    }

    public function testB()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<b>parek</b>'));
        $this->assertSame("\033[1mparek\033[0m", $result);
    }

    public function testI()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<i>parek</i>'));
        $this->assertSame("\033[3mparek\033[0m", $result);
    }

    public function testU()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<u>parek</u>'));
        $this->assertSame("\033[4mparek\033[0m", $result);
    }

    public function testP()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<p>parek</p>'));
        $this->assertSame("parek\033[0m".PHP_EOL, $result);
    }

    public function testCode()
    {
        $components = new Components();
        $result = $components->parseElement($this->getElement('<code>parek'.PHP_EOL.'    rizek<code>'));
        $this->assertSame('parek'.PHP_EOL."    rizek\033[0m\033[0m", $result);
    }

    public function testParsing()
    {
        $components = new Components();
        $dom = new \DOMDocument();
        $dom->loadHTML(<<<'HTML'
        <h1>Something <b>cool</b></h1>
        <div class="bg-yellow">
            Very nice
            <div class="bg-red">not so nice</div>
            Nice again
        </div>
        HTML);

        $this->assertSame(<<<HTML
        +----------------+
        | Something \033[1mcool\033[0m\033[0m |
        +----------------+
        \033[43mVery nice \033[43m\033[43m\033[41mnot so nice\033[41m\033[0m\033[43m\033[43m Nice again\033[43m\033[0m\033[0m
        HTML, $components->parse($dom->getElementsByTagName('body')[0]));
    }

    public function testLengt()
    {
        $this->assertSame(5, Components::lenght("\033[33mparek\033[0m"));
        $this->assertSame(5, Components::lenght("pa\033[33m\033[31mrek"));
        $this->assertSame(5, Components::lenght('parek'));
    }

    private function getElement(string $el): \DOMNode
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($el);

        return $dom->getElementsByTagName('body')[0]->firstChild;
    }
}

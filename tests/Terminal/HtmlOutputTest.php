<?php

namespace Lemon\Tests\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Terminal\HtmlOutput;
use Lemon\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class HtmlOutputTest extends TestCase
{
    public function makeOutput(string $content)
    {
        $lifecycle = new Lifecycle(__DIR__);
        $terminal = new Terminal($lifecycle);

        return new HtmlOutput($terminal, $content);
    }

    public function testParsingHeadings()
    {
        $output = $this->makeOutput('<h1>foo</h1>Lorem');
        $this->assertSame('+-----+'.PHP_EOL.'| foo |'.PHP_EOL.'+-----+'.PHP_EOL.'Lorem', $output->parse());
    }

    public function testParsingLines()
    {
        putenv('COLUMNS=10');
        $output = $this->makeOutput('foo<hr>bar');
        $this->assertSame('foo'.PHP_EOL.'——————————'.PHP_EOL.'bar', $output->parse());
    }

    public function testParsingBold()
    {
        $output = $this->makeOutput('<b>foo</b> bar');
        $this->assertSame("\033[1mfoo\033[39m bar", $output->parse());

        $output = $this->makeOutput('<strong>foo</strong> bar');
        $this->assertSame("\033[1mfoo\033[39m bar", $output->parse());
    }

    public function testParsingItalic()
    {
        $output = $this->makeOutput('<i>foo</i> bar');
        $this->assertSame("\033[3mfoo\033[39m bar", $output->parse());
    }

    public function testParsingUnderline()
    {
        $output = $this->makeOutput('<u>foo</u> bar');
        $this->assertSame("\033[4mfoo\033[39m bar", $output->parse());
    }

    public function testClasses()
    {
        $output = $this->makeOutput('<div class="text-green">foo</div>');
        $this->assertSame("\033[32mfoo\033[39m", $output->parse());

        $output = $this->makeOutput('<div class="text-yellow"><div class="text-green">foo</div>bar</div>baz');
        $this->assertSame("\033[33m\033[32mfoo\033[33m
bar\033[39m
baz", $output->parse());
    }

    public function testRemovingWhitespaces()
    {
        $output = $this->makeOutput(<<<'HTML'
            <div>foo




            </div>

            bar         baz

            <h1>     baz

            </h1>
        HTML);

        $this->assertSame('<div>foo </div> bar baz <h1> baz </h1>', $output->removeWhiteSpaces()->content);
    }
}

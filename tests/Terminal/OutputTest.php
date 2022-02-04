<?php

namespace Lemon\Tests\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Terminal\Output;
use Lemon\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{

    protected Terminal $terminal;

    /**
     * @before
     */
    public function beforeEach()
    {

        $lifecycle = new Lifecycle(__DIR__);
        putenv('COLUMNS=10'); // Setting up the terminal width for testing
        $this->terminal = new Terminal($lifecycle);
    }

    public function testParsingHeadings()
    {
        $output = new Output($this->terminal, '<h1>foo</h1>Lorem');
        $this->assertSame('+-----+' . PHP_EOL . '| foo |' . PHP_EOL . '+-----+' . PHP_EOL . 'Lorem', $output->parse());
    }

    public function testParsingLines()
    {
        $output = new Output($this->terminal, 'foo<hr>bar');
        $this->assertSame('foo' . PHP_EOL . '——————————' . PHP_EOL . 'bar', $output->parse());
    }

    public function testParsingBold()
    {
        $output = new Output($this->terminal, '<b>foo</b> bar');
        $this->assertSame("\033[1mfoo\033[39m bar", $output->parse());

        $output = new Output($this->terminal, '<strong>foo</strong> bar');
        $this->assertSame("\033[1mfoo\033[39m bar", $output->parse());
    }

    public function testParsingItalic()
    {
        $output = new Output($this->terminal, '<i>foo</i> bar');
        $this->assertSame("\033[3mfoo\033[39m bar", $output->parse());
    }

    public function testParsingUnderline()
    {
        $output = new Output($this->terminal, '<u>foo</u> bar');
        $this->assertSame("\033[4mfoo\033[39m bar", $output->parse());
    }

    public function testClasses()
    {
        $output = new Output($this->terminal, '<div class="text-green">foo</div>');
        $this->assertSame("\033[32mfoo\033[39m", $output->parse());

        $output = new Output($this->terminal, '<div class="text-yellow"><div class="text-green">foo</div>bar</div>baz');
        $this->assertSame("\033[33m\033[32mfoo\033[33mbar\033[39mbaz", $output->parse());
    }

}

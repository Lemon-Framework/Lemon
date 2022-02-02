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

    public function testParsinglines()
    {
        $output = new Output($this->terminal, 'foo<hr>bar');
        $this->assertSame('foo' . PHP_EOL . '——————————' . PHP_EOL . 'bar', $output->parse());
    }

    /*
    public function testClasses()
    {
        $output = new Output($this->terminal, '<div class="text-green">foo</div>');
        $this->assertSame("\033[32mfoo\033[39m", $output->parse());
        // WIP
    }
    */

}

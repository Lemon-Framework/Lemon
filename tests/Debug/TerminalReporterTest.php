<?php

declare(strict_types=1);

namespace Lemon\Tests\Debug;

use Exception;
use Lemon\Config\Config;
use Lemon\Contracts\Config\Config as LemonConfig;
use Lemon\Contracts\Highlighter\Highlighter as LemonHighlighter;
use Lemon\Debug\Handling\TerminalReporter;
use Lemon\Highlighter\Highlighter;
use Lemon\Kernel\Application;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class TerminalReporterTest extends TestCase
{
    public function testCode(): void
    {
        $problem = new Problem(__DIR__.DIRECTORY_SEPARATOR.'foo.php', 6);
        $app = new Application(__DIR__);
        $app->add(Highlighter::class)->alias(LemonHighlighter::class, Highlighter::class);
        $app->add(Config::class)->alias(LemonConfig::class, Config::class);
        $reporter = new TerminalReporter($problem, $app);

        $this->assertSame(<<<'HTML'
          1 | <span >1
          2 | 2
          3 | 3
          4 | 4
          5 | 5
        <span class="text-red">  6</span> | 6
          7 | 7
          8 | 8
          9 | 9
         10 | 10
         11 | 11

        HTML, $reporter->code());

        $problem = new Problem(__DIR__.DIRECTORY_SEPARATOR.'foo.php', 1);
        $reporter = new TerminalReporter($problem, $app);

        $this->assertSame(<<<'HTML'
        <span class="text-red">  1</span> | <span >1
          2 | 2
          3 | 3
          4 | 4
          5 | 5
          6 | 6

        HTML, $reporter->code());

        $problem = new Problem(__DIR__.DIRECTORY_SEPARATOR.'foo.php', 15);
        $reporter = new TerminalReporter($problem, $app);

        $this->assertSame(<<<'HTML'
         10 | 10
         11 | 11
         12 | 12
         13 | 13
         14 | 14
        <span class="text-red"> 15</span> | 15

        HTML, $reporter->code());
    }
}

class Problem extends Exception
{
    public function __construct(string $file, int $line)
    {
        $this->file = $file;
        $this->line = $line;
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Highlighter;

use Lemon\Config\Config;
use Lemon\Highlighter\Highlighter;
use Lemon\Kernel\Application;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class HighlighterTest extends TestCase
{
    public function testHighlighting(): void
    {
        $highlighter = new Highlighter(
            new Config(new Application(__DIR__))
        );

        $this->assertSame(<<<'HTML'
        <span style="color: #ebdbb2"><h1>foo</h1>
        </span><span style="color: #cc241d"><?php
        </span><span style="color: #cc241d">echo</span> <span style="color: #98971a">'cs'</span><span style="color: #ebdbb2">;</span>
        <span style="color: #cc241d">foreach</span> <span style="color: #ebdbb2">(</span><span style="color: #ebdbb2">[</span><span style="color: #ebdbb2">]</span> <span style="color: #cc241d">as</span> <span style="color: #458588">$bar</span><span style="color: #ebdbb2">)</span> <span style="color: #ebdbb2">{</span>
            <span style="color: #ebdbb2">array_map</span><span style="color: #ebdbb2">(</span><span style="color: #458588">$bar</span><span style="color: #ebdbb2">,</span> <span style="color: #689d6a">fn</span><span style="color: #ebdbb2">(</span><span style="color: #ebdbb2">)</span> <span style="color: #ebdbb2">=></span> <span style="color: #b16286">10</span> <span style="color: #ebdbb2">+</span> <span style="color: #b16286">1</span><span style="color: #ebdbb2">)</span><span style="color: #ebdbb2">;</span>
        <span style="color: #ebdbb2">}</span>
        HTML, $highlighter->highlight(<<<'HTML'
        <h1>foo</h1>
        <?php
        echo 'cs';
        foreach ([] as $bar) {
            array_map($bar, fn() => 10 + 1);
        }
        HTML));
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers;

use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Parser;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class OutputCompilerTest extends TestCase
{
    public function testEchoCompiler()
    {
        $o = new OutputCompiler();
        $this->assertSame('<?= $_env->escapeHtml($foo) ?>', $o->compileEcho('$foo', Parser::CONTEXT_HTML));
        $this->assertSame('<?= $_env->escapeScript($foo) ?>', $o->compileEcho('$foo', Parser::CONTEXT_JS));
        $this->assertSame('<?= $_env->escapeScript($foo) ?>', $o->compileEcho('$foo', Parser::CONTEXT_JS_ATTRIBUTE));
        $this->assertSame('<?= $_env->escapeAttribute($foo) ?>', $o->compileEcho('$foo', Parser::CONTEXT_ATTRIBUTE));
    }

    public function testUnescapedCompiker()
    {
        $o = new OutputCompiler();
        $this->assertSame('<?= $foo ?>', $o->compileUnescaped('$foo'));
    }

    public function testPipes()
    {
        $o = new OutputCompiler();
        $this->assertSame('<?= $_env->escapeHtml($_env->capitalize($_env->lower($foo))) ?>', $o->compileEcho('$foo|>lower|>capitalize', Parser::CONTEXT_HTML));
        $this->assertSame('<?= $_env->escapeHtml($_env->capitalize($_env->lower($foo))) ?>', $o->compileEcho('   $foo   |>  lower|>    capitalize   ', Parser::CONTEXT_HTML));

        $this->assertSame('<?= $_env->capitalize($_env->lower($foo)) ?>', $o->compileUnescaped('   $foo   |>  lower|>    capitalize   '));
    }
}

/*
 no library?
⠀⣞⢽⢪⢣⢣⢣⢫⡺⡵⣝⡮⣗⢷⢽⢽⢽⣮⡷⡽⣜⣜⢮⢺⣜⢷⢽⢝⡽⣝
⠸⡸⠜⠕⠕⠁⢁⢇⢏⢽⢺⣪⡳⡝⣎⣏⢯⢞⡿⣟⣷⣳⢯⡷⣽⢽⢯⣳⣫⠇
⠀⠀⢀⢀⢄⢬⢪⡪⡎⣆⡈⠚⠜⠕⠇⠗⠝⢕⢯⢫⣞⣯⣿⣻⡽⣏⢗⣗⠏⠀
⠀⠪⡪⡪⣪⢪⢺⢸⢢⢓⢆⢤⢀⠀⠀⠀⠀⠈⢊⢞⡾⣿⡯⣏⢮⠷⠁⠀⠀
⠀⠀⠀⠈⠊⠆⡃⠕⢕⢇⢇⢇⢇⢇⢏⢎⢎⢆⢄⠀⢑⣽⣿⢝⠲⠉⠀⠀⠀⠀
⠀⠀⠀⠀⠀⡿⠂⠠⠀⡇⢇⠕⢈⣀⠀⠁⠡⠣⡣⡫⣂⣿⠯⢪⠰⠂⠀⠀⠀⠀
⠀⠀⠀⠀⡦⡙⡂⢀⢤⢣⠣⡈⣾⡃⠠⠄⠀⡄⢱⣌⣶⢏⢊⠂⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⢝⡲⣜⡮⡏⢎⢌⢂⠙⠢⠐⢀⢘⢵⣽⣿⡿⠁⠁⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠨⣺⡺⡕⡕⡱⡑⡆⡕⡅⡕⡜⡼⢽⡻⠏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⣼⣳⣫⣾⣵⣗⡵⡱⡡⢣⢑⢕⢜⢕⡝⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⣴⣿⣾⣿⣿⣿⡿⡽⡑⢌⠪⡢⡣⣣⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⡟⡾⣿⢿⢿⢵⣽⣾⣼⣘⢸⢸⣞⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠁⠇⠡⠩⡫⢿⣝⡻⡮⣒⢽⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
 */




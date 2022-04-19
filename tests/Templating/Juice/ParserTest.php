<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Parser;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ParserTest extends TestCase
{
    private function getParser(array $tokens)
    {
        return new Parser($tokens, new OutputCompiler(), new DirectiveCompiler());
    }

    public function testParsingEcho()
    {

    }
}

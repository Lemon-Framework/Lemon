<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating;

use Lemon\Templating\Compiler;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class FactoryTest extends TestCase
{
}

class FooCompiler implements Compiler
{
    public function compile(string $template): string
    {
        return '<?php // Testing template engine ?>'.PHP_EOL.$template;
    }

    public function getExtension(): string
    {
        return 'foo';
    }
}

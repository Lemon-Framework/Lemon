<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Juice Zest
 * Provides static layer over the Lemon Juice engine.
 *
 * @method static string compile(string $template) Compiles Juice Templates.
 * @method static string getExtension() {@inheritdoc}
 * @method static static addDirectiveCompiler(string $name, string $class) Adds directive compiler class.
 *
 * @see \Lemon\Templating\Juice\Compiler
 */
class Juice extends Zest
{
    public static function unit(): string
    {
        return 'juice';
    }
}

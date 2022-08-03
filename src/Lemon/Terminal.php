<?php

declare(strict_types=1);

namespace Lemon;

use Exception;

/**
 * Lemon Terminal Zest
 * Provides static layer over the Lemon Terminal.
 *
 * @method static \Lemon\Terminal\Command command(string $signature, Closure $action, string $description = '') Creates new command
 * @method static void out(mixed $content)                                                      Outputs given content
 * @method static string ask(mixed $prompt)                                                     Asks in terminal
 * @method static void run(array $arguments)                                                    Runs CLI
 *
 * @see \Lemon\Terminal\Terminal
 */
class Terminal extends Zest
{
    public static function unit(): string
    {
        return 'terminal';
    }

    public static function run(): void
    {
        throw new Exception('Call to undefined method Terminal::run()');
    }
}

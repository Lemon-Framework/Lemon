<?php

declare(strict_types=1);

namespace Lemon\Contracts\Terminal;

use Closure;
use Lemon\Terminal\Commands\Command;

interface Terminal
{
    /**
     * Creates new command.
     */
    public function command(string $signature, Closure $action, string $description = ''): Command;

    /**
     * Outputs given content.
     */
    public function out(mixed $content): void;

    /**
     * Asks in terminal.
     */
    public function ask(mixed $prompt): string;

    /**
     * Runs CLI.
     */
    public function run(array $arguments): void;
}

<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Lemon\Terminal\Terminal;

/**
 * Class providing fundamental commands.
 */
class Commands
{
    private const COMMANDS = [
        ['serve {port?} {url?}', 'serve', 'Starts development server'],
    ];

    public function __construct(
        private Terminal $terminal
    ) {
    }

    public function load(): void
    {
        foreach (static::COMMANDS as $command) {
            $this->terminal->command($command[0], [$this, $command[1]], $command[2]);
        }
    }

    public function serve($port = 8000, $url = 'localhost'): void
    {
        exec('php -S '.$url.':'.$port);
    }
}

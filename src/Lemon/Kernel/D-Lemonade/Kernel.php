<?php

declare(strict_types=1);

namespace Lemon\Kernel\Lemonade;

require 'Helpers/commands.php';

/*
 *
 * Lemonade Kernel
 *
 * Producing system for Lemon
 *
 * */
class Kernel
{
    private $command;
    private $directory;

    public function __construct($command, $directory)
    {
        $this->command = $command;
        $this->directory = $directory;
    }

    public function execute(): void
    {
        $command = $this->command[1] ?? '';
        $arguments = array_slice($this->command, 2);
        $commands = COMMANDS;
        isset($commands[$command]) ? $commands[$command]($arguments, $this->directory) : $commands['-h']();
    }
}

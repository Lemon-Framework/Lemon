<?php

declare(strict_types=1);

namespace Lemon\Terminal;

use Lemon\Contracts\Terminal\Terminal as TerminalContract;
use Lemon\Kernel\Application;
use Lemon\Terminal\Commands\Command;
use Lemon\Terminal\Commands\Dispatcher;
use Lemon\Terminal\IO\Output;

class Terminal implements TerminalContract
{
    public readonly Dispatcher $commands;

    private Output $output;

    public function __construct(
        private Application $application
    ) {
        $this->commands = new Dispatcher();
        $this->output = new Output();
    }

    /**
     * Creates new command.
     */
    public function command(string $signature, \Closure $action, string $description = ''): Command
    {
        $command = new Command($signature, $action, $description);
        $this->commands->add($command);

        return $command;
    }

    /**
     * Outputs given content.
     */
    public function out(mixed $content): void
    {
        $out = $this->output->out($content).PHP_EOL;
        if ($this->application->runsInTerminal()) {
            echo $out;
        } else {
            file_put_contents('php://stdout', $out); // If you write to php://stdout even in server it will actualy write to standart inut which means php console pog
        }
    }

    /**
     * Asks in terminal.
     */
    public function ask(mixed $prompt): string
    {
        return readline($this->output->out($prompt));
    }

    /**
     * Returns widht of terminal.
     */
    public function width(): int
    {
        return (int) exec('tput cols');
    }

    /**
     * Returns height of terminal.
     */
    public function height(): int
    {
        return (int) exec('tput lines');
    }

    /**
     * Runs CLI.
     */
    public function run(array $arguments): void
    {
        $result = $this->commands->dispatch($arguments);

        if (is_string($result)) {
            $this->out("<div class=\"text-red\">ERROR: {$result}</div>");

            return;
        }
        $this->application->call($result[0], $result[1]);
    }
}

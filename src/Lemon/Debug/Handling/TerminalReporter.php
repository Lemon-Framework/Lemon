<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Kernel\Application;
use Throwable;

class TerminalReporter
{
    private Consultant $consultant;

    public function __construct(
        private Throwable $problem,
        private Application $app
    ) {
        $this->consultant = new Consultant();
    }

    public function report(): void
    {
        $this->app->get('terminal')->out($this->output());
    }

    public function output(): string
    {
        $severenity = Reporter::severityToString($this->problem->getCode());
        $file = $this->problem->getFile();
        $line = $this->problem->getLine();
        $message = $this->problem->getMessage();
        $hint = $this->consultant->giveAdvice($message)[0] ?? '';
        $code = $this->code();
        return <<<'HTML'
        <div class="text-red">
            <div>{$severity} - {$file}:{$line}</div>
            <div>{$message}</div>
            <div>{$hint}</div>
        </div>
        <code>{$code}</code>
        HTML;
    }

    public function code(): string
    {
        $highlighter = $this->app->highlighter;
        $code = $highlighter->highlight(file_get_contents($this->problem->getFile()));
        $lines = explode("\n", $code);
        $line = $this->problem->getLine();
        for ($i = $line - 5;)
    }
}

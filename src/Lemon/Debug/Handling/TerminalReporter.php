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

        $start = $line - 5;
        $start = $start < 0 ? 0 : $start;

        $line_count = count($lines);
        $end = $line + 6;
        $end = $end > $line_count ? $line_count : $end;

        $result = '';

        for ($i = $start; $i < $end; $i++) {
            $number = sprintf('%3i', $i);
            $number = 
                $i === $this->problem->getLine()
                ? '<span class="text-red">'.$number.'</span>'
            ;
            $result .= $i.' | '.$code[$i - 1]."\n";
        }

        return $result;
    }
}

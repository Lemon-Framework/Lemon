<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use ErrorException;
use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Highlighter\Highlighter as HighlighterContract;
use Lemon\Highlighter\Highlighter;
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

        exit;
    }

    public function output(): string
    {
        $severity =
            $this->problem instanceof ErrorException
            ? Reporter::severityToString($this->problem->getCode())
            : $this->problem::class
        ;

        $file = str_replace($this->app->directory, '', $file = $this->problem->getFile());
        $line = $this->problem->getLine();
        $message = $this->problem->getMessage();
        $hint = $this->consultant->giveAdvice($this->problem::class, $message)[0] ?? '';
        $hint = $hint ? '<p>'.$hint.'</p>' : '';
        $code = $this->code();
        $trace = $this->problem->getTrace();
        $trace = array_reduce(
            $trace,
            fn (string $carry, array $item) => isset($item['file'])
            ? $carry.'<p>'.str_replace($this->app->directory, '', $file = $item['file']).':'.$item['line'].'</p>'
            : $carry,
            ''
        );

        return "
<br>
<div class=\"text-red\">
    <p>{$severity} - {$file}:{$line}</p><p>{$message}</p>{$hint}
</div>
<hr>
<code>{$code}</code>
<br>
<p>Trace</p>
<hr>
{$trace}
";
    }

    public function code(): string
    {
        $config = $this->app->get(Config::class);
        foreach ([
            Highlighter::Declaration => 'class="text-cyan"',
            Highlighter::Statement => 'class="text-red"',
            Highlighter::Number => 'class="text-magenta"',
            Highlighter::String => 'class="text-green"',
            Highlighter::Type => 'class="text-yellow"',
            Highlighter::Comment => 'class="text-white"',
            Highlighter::Variable => 'class="text-blue"',
            Highlighter::Default => '',
        ] as $syntax => $class) {
            $config->set('highlighter.'.$syntax, $class);
        }

        $highlighter = $this->app->get(HighlighterContract::class);
        $code = $highlighter->highlight(file_get_contents($this->problem->getFile()));
        $lines = explode("\n", $code);
        $line = $this->problem->getLine();

        $start = $line - 5;
        $start = $start < 1 ? 1 : $start;
        $line_count = count($lines);
        $end = $line + 6;
        $end = $end > $line_count ? $line_count : $end;

        $result = '';

        for ($i = $start; $i < $end; ++$i) {
            $number = str_pad((string) $i, 3, ' ', STR_PAD_LEFT);
            $number =
                $i === $this->problem->getLine()
                ? '<span class="text-red">'.$number.'</span>'
                : $number
            ;

            $result .= $number.' | '.$lines[$i - 1]."\n";
        }

        return $result;
    }
}

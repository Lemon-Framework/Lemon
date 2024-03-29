<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Http\Request;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Application;
use Lemon\Templating\Template;

class Reporter
{
    public const TEMPLATE_PATH = __DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'reporter.phtml';

    private Consultant $consultant;

    public function __construct(
        private \Throwable $exception,
        private ?Request $request,
        private Application $application
    ) {
        $this->consultant = new Consultant();
    }

    public function report(): void
    {
        (new TemplateResponse($this->getTemplate(), 500))->send($this->application);
    }

    public function getTemplate(): Template
    {
        return new Template(
            static::TEMPLATE_PATH,
            static::TEMPLATE_PATH,
            $this->getData()
        );
    }

    public function getData(): array
    {
        $problem = $this->exception;

        return [
            'problem' => $problem instanceof \ErrorException
                         ? $this->severityToString($problem->getSeverity())
                         : $problem::class,
            'file' => str_replace($this->application->directory, '', $problem->getFile()),
            'line' => $problem->getLine(),
            'message' => $problem->getMessage(),
            'hint' => $this->consultant->giveAdvice($problem::class, $problem->getMessage()),
            'trace' => $this->getTrace(),
            'request' => is_null($this->request) ? [] : $this->request->toArray(),
        ];
    }

    public function getTrace(): array
    {
        $problem = $this->exception;
        $trace = array_map(
            fn ($item) => isset($item['file']) ? [
                'file' => str_replace($this->application->directory, '', $file = $item['file']),
                'code' => file_get_contents($file),
                'line' => $item['line'],
            ] : [],
            $problem->getTrace()
        );

        array_unshift($trace, [
            'file' => str_replace($this->application->directory, '', $file = $problem->getFile()),
            'code' => file_get_contents($file),
            'line' => $problem->getLine(),
        ]);

        return $trace;
    }

    public static function severityToString(int $severity): string
    {
        return match ($severity) {
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'Revocable Error',
            E_DEPRECATED => 'Deprecation',
            E_USER_DEPRECATED => 'User Deprecation',
            default => 'Error',
        };
    }
}

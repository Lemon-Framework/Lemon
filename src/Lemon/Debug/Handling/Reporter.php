<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Exception;
use Lemon\Http\Request;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Templating\Template;

class Reporter
{
    public const TEMPLATE_PATH = __DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'reporter.php';

    public function __construct(
        private Exception $exception,
        private Request $request
    ) {
    }

    public function report(): void
    {
        (new TemplateResponse($this->getTemplate(), 500))->send();
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
            'problem' => $problem::class,
            'file' => $problem->getFile(),
            'line' => $problem->getLine(),
            'message' => $problem->getMessage(),
            'hint' => '', // TODO consultant
            'trace' => $this->getTrace(),
            'request' => $this->request->toArray(),
        ];
    }

    public function getTrace(): array
    {
        $problem = $this->exception;
        $trace = array_map(
            // @phpstan-ignore-next-line
            fn ($item) => $item ? [
                'file' => $file = $item['file'],
                'code' => file_get_contents($file),
                'line' => $item['line'],
            ] : [],
            $problem->getTrace()
        );

        array_unshift($trace, [
            'file' => $file = $problem->getFile(),
            'code' => file_get_contents($file),
            'line' => $problem->getLine(),
        ]);

        return [
            'trace' => $trace,
        ];
    }
}

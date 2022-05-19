<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Config\Config;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Support\Filesystem;
use Lemon\Templating\Template;
use Throwable;

class Handler
{
    public function __construct(
        public readonly Config $config,
        public readonly ResponseFactory $response
    ) {
    }

    /**
     * Executes handler depending on debug settings.
     */
    public function handle(Throwable $problem): void
    {
        if ($this->config->part('kernel')->get('debug')) {
            // TODO improve
            $path = Filesystem::join(__DIR__, 'templates', 'reporter.phtml');
            (new TemplateResponse(new Template(
                $path,
                $path,
                [
                    'problem' => $problem::class,
                    'file' => $problem->getFile(),
                    'line' => $problem->getLine(),
                    'message' => $problem->getMessage(),
                    'hint' => '', // TODO consultant
                    'trace' => [
                        'error' => $problem->getFile(),
                        'trace' => array_merge(
                            [
                                [
                                    'file' => $problem->getFile(),
                                    'code' => file_get_contents($problem->getFile()),
                                    'line' => $problem->getLine(),
                                ]
                            ],
                            array_map(
                                fn($item) => [
                                    'file' => $file = $item['file'] ?? '',
                                    'code' => $file ? file_get_contents($file) : '',
                                    'line' => $item['line'] ?? ''
                                ], $problem->getTrace()
                            )
                        ),
                    ]
                ]
            )))->code(500)->send();
        } else {
            $this->response->error(500)->send();
        } 
    }
}

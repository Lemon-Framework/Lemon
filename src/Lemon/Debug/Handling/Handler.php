<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Config\Config;
use Lemon\Http\ResponseFactory;
use Lemon\Kernel\Application;
use Lemon\Logging\Logger;
use Throwable;

class Handler
{
    public function __construct(
        private Config $config,
        private ResponseFactory $response,
        private Logger $logger,
        private Application $application
    ) {
    }

    /**
     * Executes handler depending on debug settings.
     */
    public function handle(Throwable $problem): void
    {
        if ($this->application->runsInTerminal()) {
            echo $problem;

            return;
        }

        if ($this->config->get('debug.debug')) {
            (new Reporter($problem, $this->application->get('request'), $this->application))->report();
        } else {
            $this->response->error(500)->send();
            $this->logger->error((string) $problem);
        }
    }
}

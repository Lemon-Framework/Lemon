<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Config\Config;
use Lemon\Http\ResponseFactory;
use Lemon\Kernel\Application;
use Lemon\Contracts\Logging\Logger;

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
    public function handle(\Throwable $problem): void
    {
        if ($this->application->runsInTerminal()) {
            (new TerminalReporter($problem, $this->application))->report();
        }

        if ($this->config->get('debug.debug')) {
            $request = $this->application->has('request') ? $this->application->get('request') : null;
            (new Reporter($problem, $request, $this->application))->report();
        } else {
            $this->response->error(500)->send($this->application);
            $this->logger->error((string) $problem);
        }
    }
}

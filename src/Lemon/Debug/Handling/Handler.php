<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Exception;
use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Kernel\Lifecycle;
use Lemon\Logging\Logger;

class Handler
{
    public function __construct(
        private Config $config,
        private ResponseFactory $response,
        private Logger $logger,
        private Lifecycle $lifecycle
    ) {
    }

    /**
     * Executes handler depending on debug settings.
     */
    public function handle(Exception $problem): void
    {
        if ($this->lifecycle->runsInTerminal()) {
            echo $problem;

            return;
        }

        if ($this->config->get('debug.debug')) {
            (new Reporter($problem, $this->lifecycle->get('request')))->report();
        } else {
            $this->response->error(500)->send();
            $this->logger->error((string) $problem);
        }
    }
}

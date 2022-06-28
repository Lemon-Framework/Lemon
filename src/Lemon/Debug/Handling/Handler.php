<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Exception;
use Lemon\Config\Config;
use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;

class Handler
{
    public function __construct(
        private Config $config,
        private ResponseFactory $response,
        private Request $request
    ) {
    }

    /**
     * Executes handler depending on debug settings.
     */
    public function handle(Exception $problem): void
    {
        if ($this->config->get('kernel.debug')) {
            (new Reporter($problem, $this->request))->report();
        } else {
            $this->response->error(500)->send();
            // TODO logging
        }
    }
}

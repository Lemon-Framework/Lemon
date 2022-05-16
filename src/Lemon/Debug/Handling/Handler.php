<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Config\Config;
use Lemon\Http\ResponseFactory;
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
            echo $problem; // TODO Reporter
        } else {
            $this->response->error(500)->send();
        }
            
    }
}

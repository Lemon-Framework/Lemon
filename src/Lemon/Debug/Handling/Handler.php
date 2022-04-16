<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Http\Response;
use Lemon\Kernel\Lifecycle;

class Handler
{
    public readonly mixed $problem;

    public readonly Lifecycle $lifecycle;

    public function __construct($problem, Lifecycle $lifecycle)
    {
        $this->problem = $problem;
        $this->lifecycle = $lifecycle;
    }

    /**
     * Executes handler depending on debug settings.
     */
    public function terminate(): void
    {
        if ($this->lifecycle->config('kernel', 'debug')) {
            echo $this->problem;
        } // TODO REPORTER
        else {
            (new Response('', 500))->terminate();
        }
    }
}

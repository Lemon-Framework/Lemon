<?php

declare(strict_types=1);

namespace Lemon\Contracts\Debug;

use Exception;

interface Handler
{
    /**
     * Executes handler depending on debug settings.
     */
    public function handle(Exception $problem): void;
}

<?php

declare(strict_types=1);

namespace Lemon\Contracts\Debug;

interface Handler
{
    /**
     * Executes handler depending on debug settings.
     */
    public function handle(\Exception $problem): void;
}

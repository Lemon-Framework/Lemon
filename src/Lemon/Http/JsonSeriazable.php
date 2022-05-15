<?php

declare(strict_types=1);

namespace Lemon\Http;

/**
 * Interface 
 *
 */
interface JsonSeriazable
{
    public function getJson(): string;
}

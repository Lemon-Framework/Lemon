<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Template Zest
 * Provides static layer over the Lemon Templating.
 *
 * @see 
 */
class Template extends Zest
{
    public static function unit(): string
    {
        return 'templating';
    }
}

<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Template Zest
 * Provides static layer over the Lemon Templating.
 *
 * @see \Lemon\Template\Template
 */
class Template extends Zest
{
    public static function unit(): string
    {
        return 'templating';
    }
}

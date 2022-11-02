<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Template Zest
 * Provides static layer over the Lemon Templating.
 *
 * @method static \Lemon\Templating\Template make(string $name, array $data = [])             Manages and generates templates.
 * @method static string|false               getRawPath(string $name)                         Returns path of raw template.
 * @method static string                     getCompiledPath(string $name)                    Returns path of compiled template.
 * @method static void                       compile(string $raw_path, string $compiled_path) Compiles template and caches it.
 *
 * @see \Lemon\Templating\Factory
 */
class Template extends Zest
{
    public static function unit(): string
    {
        return 'templating';
    }
}

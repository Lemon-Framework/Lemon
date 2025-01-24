<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

/**
 * Holds available html nodes
 */
class HtmlNodes
{
    private array $singleton = [
        '!doctype',
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'link',
        'meta',
        'source',
        'track',
        'wb',
    ];

    public function isSingleton(string $name): bool
    {
        return in_array(strtolower($name), $this->singleton);
    }
}

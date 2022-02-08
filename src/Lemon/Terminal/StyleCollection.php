<?php

namespace Lemon\Terminal;

class StyleCollection
{
    public array $colors = [
        'black' => 0,
        'red' => 1,
        'green' => 2,
        'yellow' => 3,
        'blue' => 4,
        'magenta' => 5,
        'cyan' => 6,
        'white' => 7
    ];

    public array $classes = [
        'text-(\w+)' => 'handleTextColor',
        'bg-(\w+)' => 'handleBackgroundColor',
    ];

    private Terminal $terminal;

    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    public function resolveClass(string $class)
    {
        foreach ($this->classes as $pattern => $handler) {
            if (preg_match("/^$pattern$/", $class, $matches)) {
                return $this->{$handler}($matches);
            }
        }

        return ['', ''];
    }

    public function handleTextColor($matches)
    {
        $color = $matches[1];
        if (!isset($this->colors[$color])) {
            return ['', ''];
        }

        $code = 30 + $this->colors[$color];

        return ["\033[{$code}m", '<PARENT>'];
    }

    public function handleBackgroundColor($matches)
    {
        $color = $matches[1];
        if (!isset($this->colors[$color])) {
            return ['', ''];
        }

        $code = 40 + $this->colors[$color];

        return ["\033[{$code}m", '<PARENT>'];
    }
}

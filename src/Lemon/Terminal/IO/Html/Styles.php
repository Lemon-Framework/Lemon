<?php

declare(strict_types=1);

namespace Lemon\Terminal\IO\Html;

use DOMNode;
use Lemon\Terminal\Exceptions\HtmlException;

class Styles
{
    private array $styles = [
        'text-black' => ['%e30m', '', ''],
        'text-red' => ['%e31m', '', ''],
        'text-green' => ['%e32m', '', ''],
        'text-yellow' => ['%e33m', '', ''],
        'text-blue' => ['%e34m', '', ''],
        'text-magenta' => ['%e35m', '', ''],
        'text-cyan' => ['%e36m', '', ''],
        'text-white' => ['%e37m', '', ''],
        'bg-black' => ['%e40m', '', ''],
        'bg-red' => ['%e41m', '', ''],
        'bg-green' => ['%e42m', '', ''],
        'bg-yellow' => ['%e43m', '', ''],
        'bg-blue' => ['%e44m', '', ''],
        'bg-magenta' => ['%e45m', '', ''],
        'bg-cyan' => ['%e46m', '', ''],
        'bg-white' => ['%e47m', '', ''],
    ];

    public function getStyle(DOMNode $node): array
    {
        if (!isset($node->attributes['class'])) {
            return ['', '', ''];
        }

        $result = ['', '', ''];
        foreach (explode(' ', $node->attributes['class']->value) as $class) {
            if (!isset($this->styles[$class])) {
                throw new HtmlException('Class '.$class.' does not exist');
            }
            $style = $this->styles[$class];

            $style = str_replace('%e', "\033[", $style);

            foreach ($style as $index => $part) {
                $result[$index] .= $part;
            } 
        }

        return $result;
    }

}

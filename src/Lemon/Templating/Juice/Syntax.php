<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

/**
 * Stores tag syntax for Juice.
 */
final class Syntax
{

    public readonly string $re;

    public function __construct(
        public readonly string $directive = '\{\s*([^\{!#].*?)(?:\s+?([^\s].+?[^!\}#]))?\s*\}',
        public readonly string $end = '(?:end|\/)(.+)',
        public readonly string $output = '\{\{\s*(.+?)\s*\}\}',
        public readonly string $unsafe = '\{!\s*(.+?)\s*!\}',
        public readonly string $comment = '\{#.+?#\}',
        public readonly string $escape = '\\'
    ) {
        $this->re = $this->buildRe();
    }

    /**
     * Returns syntax bundle of blade-like syntax.
     */
    public static function blade(): self
    {
        // TODO tests
        return new self(
            '\B@([^\(]+)(?(?=\()\((.+?)\))',
            'end(.+)',
            '\{\{[^-]\s*(.+?)\s*[^-]\}\}',
            '{!!\s*(.+?)\s*!!}',
            '{{--.+?--}}',
            '@'
        );
    }

    /**
     * Returns syntax bundle of twig-like syntax.
     */
    public static function twig(): self
    {
        // TODO tests
        return new self(
            '\{%\s*(.*?)(?:\s+?([^\s].+?))?\s*%\}'
        );
    }

    private function buildRe(): string
    {
        $escape = "(?<!{$this->escape})";
        return "~
            (?<HtmlStart>\<)
            |(?<HtmlEnd>\>)
            |(?<HtmlClose>\</)
            |(?<StringDelim>\"|')
            |(?<Directive>{$escape}{$this->directive})
            |(?<Output>{$escape}{$this->output})
            |(?<Unsafe>{$escape}{$this->unsafe})
            |(?<Comment>{$escape}{$this->comment})
            |(?<Space>\s+)
            |(?<Text>.+)
            ~xsA"
        ;
    }
}

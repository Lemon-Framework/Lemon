<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;


/**
 * Stores tag syntax for Juice.
 */
final class Syntax
{
    public readonly string $regex;
    public readonly string $escape_regex;

    public function __construct(
        public readonly string $tag = '\{\s*([^\{!#].*?)(?:\s+?([^\s].+?[^!\}#]))?\s*\}',
        public readonly string $end = '(?:end|\/)(.+)',
        public readonly string $echo = '\{\{\s*(.+?)\s*\}\}',
        public readonly string $unescaped = '\{!\s*(.+?)\s*!\}',
        public readonly string $comment = '\{#.+?#\}',
        public readonly string $escape = '\\'
    ) {
        $this->regex = $this->buildRegex();
        $this->escape_regex = $this->buildEscapeRegex();
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

    /**
     * Builds regular expression used for lexing.
     */
    private function buildRegex(): string
    {
        $escape = '(?<!'.preg_quote($this->escape).')';
        return "/({$escape}{$this->tag})|({$escape}{$this->echo})|({$escape}{$this->unescaped})|({$escape}{$this->comment})/";
    }

    /**
     * Builds regular expression used for escapment.
     */
    private function buildEscapeRegex(): string
    {
        $escape = preg_quote($this->escape);
        return "/({$escape}({$this->tag}))|({$escape}({$this->echo}))|({$escape}({$this->unescaped}))|({$escape}({$this->comment}))/";
    }
}

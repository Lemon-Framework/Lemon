<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

/**
 * Stores tag syntax for Juice.
 *
 * @property string $tag
 * @property string $echo
 * @property string $unescaped
 * @property string $end
 * @property string $comment
 * @property string $regex
 */
final class Syntax
{
    use Properties;

    #[Read]
    private string $regex;

    public function __construct(
        #[Read]
        private string $tag = '\{\s*([^\{!#].*?)(?:\s+?([^\s].+?[^!\}#]))?\s*\}',
        #[Read]
        private string $end = '(?:end|\/)(.+)',
        #[Read]
        private string $echo = '\{\{\s*(.+?)\s*\}\}',
        #[Read]
        private string $unescaped = '\{!\s*(.+?)\s*!\}',
        #[Read]
        private string $comment = '\{#.+?#\}',
    ) {
        $this->buildRegex();
    }

    public static function blade(): self
    {
        // TODO tests
        return new self(
            '@([^\(]+)(?(?=\()\((.+?)\))',
            'end(.+)',
            '\{\{[^-]\s*(.+?)\s*[^-]\}\}',
            '{!!\s*(.+?)\s*!!}',
            '{{--.+?--}}'
        );
    }

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
    private function buildRegex()
    {
        $this->regex = "/({$this->tag})|({$this->echo})|({$this->unescaped})/";
    }
}

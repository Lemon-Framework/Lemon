<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

/**
 * Stores tag syntax for Juice.
 *
 * @property-read string $tag
 * @property-read string $echo
 * @property-read string $unescaped
 * @property-read string $end
 * @property-read string $comment
 * @property-read string $regex
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
        private string $end = 'end(.+)',
        #[Read]
        private string $echo = '\{\{\s*(.+?)\s*\}\}',
        #[Read]
        private string $unescaped = '\{!\s*(.+?)\s*!\}',
        #[Read]
        private string $comment = '\{#.+#\}',
    ) {
        $this->buildRegex();
    }

    /**
     * Prepares regex to be usable in lexer.
     */
    private function prepare(string $target)
    {
        // This regex removes every () except the ones taht already have \
        // so if we are compiling, we won't have bad matches
        return preg_replace('/(?<!\\\\)(\(|\))/', '', $target);
    }

    /**
     * Builds regular expression used for lexing.
     */
    private function buildRegex()
    {
        $this->regex = "/({$this->tag})|({$this->echo})|({$this->unescaped})/";
    }
}

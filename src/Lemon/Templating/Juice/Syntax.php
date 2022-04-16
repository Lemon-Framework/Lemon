<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

/**
 * Stores tag syntax for juice.
 *
 * @property string $tag
 * @property string $echo
 * @property string $unescaped
 * @property string $regex
 */
final class Syntax
{
    use Properties;

    #[Read]
    private string $regex;

    public function __construct(
        #[Read]
        private string $tag,
        #[Read]
        private string $echo,
        #[Read]
        private string $unescaped
    ) {
        $this->buildRegex();
    }

    /**
     * Prepares regex to be usable in lexer.
     */
    private function prepare(string $target)
    {
        // This regex adds \ to every () except the ones taht already have
        // it so if we are compiling, we won't have bad matches
        return preg_replace('/(?<!\\\\)(\(|\))/', '\\$1', $target);
    }

    /**
     * Builds regular expression used for lexing.
     */
    private function buildRegex()
    {
        $this->regex = "/({$this->prepare($this->tag)})|({$this->prepare($this->echo)})|({$this->prepare($this->unescaped)})/";
    }
}

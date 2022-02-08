<?php

namespace Lemon\Terminal;

class Output
{
    private Terminal $terminal;

    private string $content;

    public function __construct(Terminal $terminal, string $content)
    {
        $this->terminal = $terminal;
        $this->content = $content;
    }

    public function resolve()
    {
        if (preg_match('/<.+?>/', $this->content)) {
            return (new HtmlOutput($this->terminal, $this->content))->parse();
        }

        return $this->content;
    }
}

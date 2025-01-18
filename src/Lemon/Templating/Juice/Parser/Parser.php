<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Contracts\Templating\Juice\Lexer;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Nodes\Html\Node as HtmlNode;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Token\HtmlTokenKind;

class Parser
{
    public function __construct( 
        public readonly Lexer $lexer,
    ) {
    }

    public function parse(): NodeList
    {
        $list = new NodeList();
        $this->lexer->changeContext(Context::Html);
        while ($this->lexer->next()) {
            $list->add(
                $this->parseHtmlTag()
            );
        }   

        return $list;    
    }

    public function parseHtmlTag(): ?HtmlNode
    {
        if ($this->lexer->current()->kind != HtmlTokenKind::TagOpen) {
            return null;
        }

        $this->lexer->changeContext(Context::HtmlTag);

        if ($this->lexer->next()->kind !== HtmlTokenKind::Name) {
            throw new CompilerException("Unexpected token after <, expected tag name!"); // @hint If you want to write < symbol, use "&lt;" 
        }

        $this->lexer->changeContext(Context::Html);

        return null;
    }
}

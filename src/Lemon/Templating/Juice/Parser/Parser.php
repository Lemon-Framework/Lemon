<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Contracts\Templating\Juice\Lexer;
use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Nodes\Html\Attribute;
use Lemon\Templating\Juice\Nodes\Html\Node as HtmlNode;
use Lemon\Templating\Juice\Nodes\Html\StringLiteral;
use Lemon\Templating\Juice\Nodes\Html\Text;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Token\HtmlTokenKind;
use Lemon\Templating\Juice\Token\TokenKind;

class Parser
{
    public function __construct( 
        public readonly Lexer $lexer,
    ) {
    }

    public function line(): int 
    {
        return $this->lexer->current()->line;
    }

    public function pos(): int
    {
        return $this->lexer->current()->pos;
    }

    public function parse(?callable $end = null, ?callable $error = null): NodeList
    {
        $list = new NodeList();
        $this->lexer->changeContext(Context::Html);
        $didnt_end = true;
        while ($this->lexer->peek() && ($didnt_end = !$end || !$end())) {
            $this->lexer->next();
            $list->add(
                $this->parseHtmlTag()
                ?? $this->parseText()
            );
        }

        if ($didnt_end) {
            $error();
        }

        return $list;    
    }

    public function parseHtmlTag(): ?HtmlNode
    {
        if ($this->lexer->current()?->kind != HtmlTokenKind::TagOpen) {
            return null;
        }

        $line = $this->line();
        $pos = $this->pos();
        $this->lexer->changeContext(Context::HtmlTag);

        if ($this->lexer->next()?->kind !== HtmlTokenKind::Name) {
            throw new CompilerException("Unexpected token after <, expected tag name!"); // @hint If you want to write < symbol, use "&lt;" 
        }

        $name = $this->lexer->current()->content;

        $attributes = new NodeList();
        while ($attribute = $this->parseAttribute()) {
            $attributes->add($attribute);
        }

        if ($this->lexer->current()->kind != HtmlTokenKind::TagClose) {
            throw new CompilerException("Unexpected token, expected either attribute or >!");
        }

        $this->lexer->changeContext(Context::Html);

        $this->parse(
            fn() => $this->parseClosingHtmlTag($name), 
            fn() => throw new CompilerException("Unlosed tag {$name}", $line, $pos)
        );

        return null;
    }

    public function parseClosingHtmlTag(string $name): bool
    {
        if ($this->lexer->peek()?->kind !== HtmlTokenKind::TagClose) {
            return false;
        }
        $this->lexer->next();
        $this->lexer->changeContext(Context::HtmlTag);
        if ($this->lexer->next()?->kind !== HtmlTokenKind::Name) {
            throw new CompilerException('Unexpected token!', $this->line(), $this->pos());
        }

        if ($this->lexer->current()->content !== $name) {
            throw new CompilerException('Unclosed tag '.$name, $this->line(), $this->pos());
        }

        if ($this->lexer->next()?->kind !== HtmlTokenKind::EndTagOpen) {
            throw new CompilerException('Unexpected token!', $this->line(), $this->pos());
        }

        $this->lexer->changeContext(Context::Html);

        return true;
    }

    public function parseAttribute(): ?Attribute 
    {
        if ($this->lexer->next()->kind !== HtmlTokenKind::Name) {
            return null;
        }

        $name = $this->lexer->current()->content;

        if ($this->lexer->next()->kind !== HtmlTokenKind::Equals) {
            return new Attribute($name);
        }

        $this->lexer->next();
        $content = $this->parseString();
        
        return new Attribute($name, $content);
    }

    public function parseString(): ?NodeList
    {
        if ($this->lexer->current()->kind !== HtmlTokenKind::StringDelim) {
            return null;
        }

        $start = $this->lexer->current();

        $result = new NodeList(); 
        while (($current = $this->lexer->next())?->content !== $start->content) {
            if ($current === null) {
                throw new CompilerException('Unclosed string!', $start->line, $start->pos);
            }
            // todo ast wit positions
            $result->add(match($current->kind) {
                HtmlTokenKind::EscapedStringDelim => new StringLiteral($current->content),
                HtmlTokenKind::StringContent => new StringLiteral($current->content),
                HtmlTokenKind::StringDelim => new StringLiteral($current->content),
                // todo juice 
                // if this happens we're cooked
                default => throw new CompilerException('Internal error within compiler, open issue please', $start->line, $start->pos),
            });
        }

        return $result;
    }

    public function parseText(): Text
    {
        return new Text($this->lexer->current()->content);
    }
}

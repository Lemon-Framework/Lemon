<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Token;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Syntax;

final class Token
{
    public function __construct(
        public TokenKind $kind,
        public readonly int $line,
        public readonly int $pos,
        public string $content = '',
    ) {
    }

    /**
     * This function prevents kind colision by using context that is known while parsing
     *
     * todo make it more generic
     */
    public function resolveKind(Syntax $syntax, Context $context): self 
    {
        if ($context === Context::Html) {
            if ($this->kind instanceof HtmlTokenKind) {
                return $this;
            }

            if ($this->content === '=') {
                $this->kind = HtmlTokenKind::Equals;
                return $this;
            }

            if ($this->kind instanceof PHPTokenKind) {
                $this->kind = HtmlTokenKind::Text;
                return $this;
            }

            return $this;
        }

        if ($context == Context::JuiceUnclosed && $this->kind == JuiceTokenKind::Closing) {
            // there is some unclosed bracket here, so juice cant end here, therefore ending symbol will be threated as some other symbol
            foreach ($syntax->tokens() as [$kind, $re]) {
                if (preg_match("/{$re}/", $this->content) === 1) {
                    $this->kind = PHPTokenKind::{$kind}; 
                    return $this;     
                }
            }

            return $this;
        }

        // todo make it thru content not kind -> in theory the <> operators dont have to exist hh
        // lex the html symbols here ig
        $this->kind = match ($this->kind) {
            HtmlTokenKind::TagOpen => PHPTokenKind::Operator,
            HtmlTokenKind::TagClose => PHPTokenKind::Operator,
            default => $this->kind,
        };

        return $this;
    }

}

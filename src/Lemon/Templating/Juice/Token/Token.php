<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Token;

use Lemon\Templating\Juice\Context;

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
     */
    public function resolveKind(Context $context): self 
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

        // juice ctx

        $this->kind = match ($this->kind) {
            HtmlTokenKind::TagOpen => PHPTokenKind::BinaryOperator,
            HtmlTokenKind::TagClose => PHPTokenKind::BinaryOperator,
            default => $this->kind,
        };

        return $this;
    }

}

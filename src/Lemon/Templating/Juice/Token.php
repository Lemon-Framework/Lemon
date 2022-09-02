<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

final class Token
{
    public function __construct(
        public TokenKind $kind,
        public readonly int $line,
        public readonly int $pos,
        public string $name = '',
        private array $content = [],
        private array $children = [],
    ) {
    }

    public function addChild(Token $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addToContent(Token $token): self
    {
        $this->content[] = $token;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}

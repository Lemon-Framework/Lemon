<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

final class Token
{
    public function __construct(
        public TokenKind $kind,
        public readonly int $line,
        public readonly int $pos,
        public string $content = '',
    ) {
    }
}

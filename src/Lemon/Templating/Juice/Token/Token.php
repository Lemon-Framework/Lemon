<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Token;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\Syntax;

final class Token
{
    public function __construct(
        public readonly TokenKind $kind,
        public readonly Position $position,
        public readonly string $content = '',
    ) {
    }
}

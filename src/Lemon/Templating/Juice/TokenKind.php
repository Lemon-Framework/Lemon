<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

enum TokenKind: int
{
    case TAG = 0;
    case OUTPUT = 1;
    case UNESCAPED = 2;
    case TEXT = 3;
}

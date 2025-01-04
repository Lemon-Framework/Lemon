<?php

namespace Lemon\Templating\Juice\Token;

enum JuiceTokenKind implements TokenKind
{
    // Juice
    case DirectiveStart;

    case EndDirectiveStart;

    case OutputStart;

    case UnsafeStart;

    case CommentStart;

    case Closing;

    case Escape;
}

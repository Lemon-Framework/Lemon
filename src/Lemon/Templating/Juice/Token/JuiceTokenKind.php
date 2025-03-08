<?php

namespace Lemon\Templating\Juice\Token;

enum JuiceTokenKind implements TokenKind
{
    // todo dont use start but open damn this will need sum refactoring
    case DirectiveStart;

    case EndDirective;

    case OutputStart;

    case UnsafeStart;

    case CommentStart;

    case DirectiveEnd;

    case OutputEnd;

    case UnsafeEnd;

    case CommentEnd;

    case Escape;
}

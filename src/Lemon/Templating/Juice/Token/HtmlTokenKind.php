<?php

namespace Lemon\Templating\Juice\Token;

enum HtmlTokenKind implements TokenKind
{
    // Hmtl
    case TagOpen;

    case Name;

    case TagClose;

    case EndTagOpen;

    case CommentOpen;

    case CommentClose;

    case Equals;

    case Text;

    case Space;

    case StringDelim;

    case EscapedStringDelim;

    case StringContent;
}

<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

enum TokenKind
{
    case Document;

    // Hmtl
    case HtmlTag;

    case HtmlAttribute;

    case HtmlComment;

    // Text
    case Text;

    // Juice
    case Directive;

    case Output;

    case UnsafeOutput;
}

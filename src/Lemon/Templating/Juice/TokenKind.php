<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

enum TokenKind
{
    // Hmtl
    case HtmlTagOpen;

    case HtmlTagClose;

    case HtmlEndTag;

    case HtmlCommentOpen;

    case HtmlCommentClose;

    // Juice
    case DirectiveStart;

    case EndDirectiveStart;

    case OutputStart;

    case UnsafeStart;

    case CommentStart;

    case Closing;

    case Escape;

    // PHP
    // You can basicaly say some things (such as arrows and stuff) are operators
    case BinaryOperator;
    case UnaryOperator;
    case OpeningBracket;
    case ClosingBracket;
    case OpeningSquareBracket;
    case ClosingSquareBracket;
    case DoubleArrow;
    case QuestionMark;
    case Colon;
    case Comma;
    case Fn;
    case Number;
    case Variable;
    case Name;

    // Global
    case StringDelim;

    case Space;

    case Text;
}


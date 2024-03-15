<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

enum TokenKind
{
    // Hmtl
    case HtmlTagStart;

    case HtmlTagEnd;

    case HtmlCloseTag;

    case HtmlStringDelim;

    case HtmlComment;

    case HtmlSpace;

    // Text
    case Text;

    // Juice
    case DirectiveStart;

    case DirectiveEnd;

    case Output;

    case UnsafeOutput;

    case Comment;

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
    case String;
    case Number;
    case Variable;
    case Name;
}


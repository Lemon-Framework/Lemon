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
    case BinaryOperator;
    case UnaryOperator;
    case Not;
    case OpeningBracket;
    case ClosingBracket;
    case OpeningSquareBracket;
    case ClosingSquareBracket;
    case Pipe;
    case Arrow;
    case NullArrow;
    case DoubleArrow;
    case DoubleColon;
    case QuestionMark;
    case Colon;
    case As;
    case In;
    case Instanceof;
    case New;
    case Elipsis;
    case Comma;
    case Fn;
    case String;
    case Number;
    case Variable;
    case Name;
}


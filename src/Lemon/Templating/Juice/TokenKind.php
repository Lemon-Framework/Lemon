<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

enum TokenKind
{
    // Hmtl
    case HtmlTagStart;
    case HtmlEndTagStart;
    case HtmlTagEnd;
    case HtmlName;
    case HtmlComment;

    // Text
    case Text;

    // Juice
    case Directive;

    case Output;

    case UnsafeOutput;

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


<?php

namespace Lemon\Templating\Juice\Token;

enum PHPTokenKind implements TokenKind
{
    // PHP
    // You can basicaly say some things (such as arrows and stuff) are operators
    case Operator;
    case OpenningBracket;
    case ClosingBracket;
    case OpenningSquareBracket;
    case ClosingSquareBracket;
    case OpenningBrace;
    case ClosingBrace;
    case DoubleArrow;
    case QuestionMark;
    case Colon;
    case Comma;
    case Fn;
    case Number;
    case Variable;
    case Name;
    case StringDelim;
    case As;
    case In;
    case Instanceof;
}

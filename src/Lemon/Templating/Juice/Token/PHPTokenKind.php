<?php

namespace Lemon\Templating\Juice\Token;

enum PHPTokenKind implements TokenKind
{
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
    case StringDelim;
}

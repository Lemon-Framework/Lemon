<?php

declare(strict_types=1);

namespace Lemon\Highlighter;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Highlighter\Highlighter as HighlighterContract;
use PhpToken;

class Highlighter implements HighlighterContract
{
    public const 
        Declaration = 0,
        Statement = 1,
        Number = 2,
        String = 3,
        Type = 4,
        Comment = 5,
        Variable = 6,
        Default = 7
    ;

    public const TokenToColor = [
        T_ABSTRACT => self::Declaration,
        T_AS => self::Statement,
        T_ATTRIBUTE => self::Statement,
        T_BOOL_CAST => self::Type,
        T_BREAK => self::Statement,
        T_CALLABLE => self::Type,
        T_CASE => self::Statement,
        T_CATCH => self::Statement,
        T_CLASS => self::Declaration,
        T_CLONE => self::Statement,
        T_CLOSE_TAG => self::Statement,
        T_COMMENT => self::Comment,
        T_CONST => self::Statement,
        T_CONSTANT_ENCAPSED_STRING => self::String,
        T_CONTINUE => self::Statement,
        T_DECLARE => self::Statement,
        T_DEFAULT => self::Statement,
        T_DNUMBER => self::Number,
        T_DO => self::Statement,
        T_DOC_COMMENT => self::Comment,
        T_DOUBLE_CAST => self::Variable,
        T_ECHO => self::Statement,
        T_ELSE => self::Statement,
        T_ELSEIF => self::Statement,
        T_ENDDECLARE => self::Statement,
        T_ENDFOR => self::Statement,
        T_ENDFOREACH => self::Statement,
        T_ENDIF => self::Statement,
        T_ENDSWITCH => self::Statement,
        T_ENDWHILE => self::Statement,
        T_ENUM => self::Declaration,
        T_EXIT => self::Statement,
        T_FINAL => self::Declaration,
        T_FINALLY => self::Statement,
        T_FN => self::Declaration,
        T_FOR => self::Statement,
        T_FOREACH => self::Statement,
        T_FUNCTION => self::Declaration,
        T_GLOBAL => self::Statement,
        T_GOTO => self::Statement,
        T_IF => self::Statement,
        T_IMPLEMENTS => self::Declaration,
        T_INCLUDE => self::Declaration,
        T_INCLUDE_ONCE => self::Declaration,
        T_INSTANCEOF => self::Statement,
        T_INSTEADOF => self::Declaration,
        T_INTERFACE => self::Declaration,
        T_INT_CAST => self::Type,
        T_LNUMBER => self::Number,
        T_MATCH => self::Statement,
        T_NAMESPACE => self::Declaration,
        T_NEW => self::Declaration,
        T_OBJECT_CAST => self::Variable,
        T_OPEN_TAG => self::Statement,
        T_PRINT => self::Statement,
        T_PRIVATE => self::Declaration,
        T_PROTECTED => self::Declaration,
        T_PUBLIC => self::Declaration,
        T_READONLY => self::Declaration,
        T_REQUIRE => self::Declaration,
        T_REQUIRE_ONCE => self::Declaration,
        T_STATIC => self::Declaration,
        T_STRING_CAST => self::Type,
        T_SWITCH => self::Statement,
        T_THROW => self::Statement,
        T_TRAIT => self::Declaration,
        T_TRY => self::Statement,
        T_USE => self::Declaration,
        T_VARIABLE => self::Variable,
        T_YIELD => self::Statement,
        T_YIELD_FROM => self::Statement
    ];

    public function __construct(
        public readonly Config $config 
    ) {

    }

    public function highlight(string $code): string
    {
        $result = '';
        foreach (PhpToken::tokenize($code) as $token) {
            if ($token->is(T_WHITESPACE)) {
                $result .= $token->text;
                continue;
            }

            $color = self::TokenToColor[$token->id] ?? self::Default;
            $html = $this->config->get('highlighter.'.$color);
            $text = $token->text;
            $result .= "<span {$html}>{$text}</span>";
        }

        return $result;
    }
}

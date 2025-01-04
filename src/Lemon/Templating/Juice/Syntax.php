<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;



/**
 * Stores tag syntax for Juice.
 *
 * todo add svelte blade twig :crazy:
 */
final class Syntax
{

    private array $tokens = [
        ['OpenningBracket', '\('],
        ['ClosingBracket', '\)'], 
        ['OpenningSquareBracket', '\['],
        ['ClosingSquareBracket', '\]'],
        ['OpenningBrace', '\{'],
        ['ClosingBrace', '\}'],
        ['DoubleArrow', '=\>'],
        ['Operator', '[\!\@\#\%\^\&\*\+\<\>\.\/\|\=\~\?\-\:]+'],
        ['QuestionMark', '\?'],
        ['Colon', ':'],
        ['Comma', ','],
        ['Fn', 'fn'],
        ['As', 'as'],
        ['In', 'in'],
        ['Instanceof', 'instanceof'],
        ['Number', '(-?\d+(\.\d+)?)'],
        ['Variable', '\$([a-zA-Z][a-zA-Z0-9]*)'],
        ['Name', '[a-zA-Z][a-zA-Z0-9]*'],
    ];


    public readonly string $re;

    public readonly Operators $operators;

    /**
     * Describes core syntax of juice
     *
     * @param array{string, string} $directive Describes tokens of directives
     * @param string $end Describes tokens of directive ending tag
     * @param array{string, string} $output Describes tokens of output tag
     * @param array{string, string} $unsafe Describes tokens of unescaped output tag
     * @param array{string, string} $comment Describes tokens of comment tag
     * @param string $escape Escape token that is used to ignore parsing
     */
    public function __construct(
        public readonly array $directive = ['\[\s*((?&DIRECTIVE_NAME))', '\]'],
        public readonly string $end = '\[\s*(?:\/|end)((?&DIRECTIVE_NAME))\s*\]',
        public readonly array $output = ['\[\[', '\]\]'], 
        public readonly array $unsafe = ['\[!', '!\]'], 
        public readonly array $comment = ['\[\-\-', '\-\-\]'],
        public readonly string $escape = '@',
        Operators $operators = null,
    ) {
        $this->operators = $operators ?? new Operators();
        //$this->tokens[] = ['UnaryOperator', $this->operators->buildUnaryRe()];
        //$this->tokens[] = ['BinaryOperator', $this->operators->buildBinaryRe()];
        $this->re = $this->buildRe();
    }

    private function buildRe(): string
    {
        $closing = [$this->directive[1], $this->output[1], $this->unsafe[1], $this->comment[1]];
        usort($closing,
                fn(string $a, string $b) => strlen($b) - strlen($a)
        ); // they have to be sorted by their length so the first is the 
           // longest in order to lex properly

        $closing = implode('|', $closing);

        $expression_tokens = '';

        foreach ($this->tokens as [$name, $re]) {
            $expression_tokens .= "|(?<PHP_{$name}>{$re})";
        }

        return "/
            (?(DEFINE)(?<DIRECTIVE_NAME>[a-zA-Z][a-zA-Z0-9]+))
            (?<Html_EndTagOpen>\<\/)
            |(?<Html_TagOpen>\<)
            |(?<Html_TagClose>\>)
            |(?<Html_CommentOpen>\<!\-\-)
            |(?<Html_CommentClose>\-\-\>)
            |(?<Html_StringDelim>\"|')
            |(?<Juice_Escape>{$this->escape})
            |(?<Juice_DirectiveStart>{$this->directive[0]})
            |(?<Juice_EndDirective>{$this->end})
            |(?<Juice_OutputStart>{$this->output[0]})
            |(?<Juice_UnsafeStart>{$this->unsafe[0]})
            |(?<Juice_CommentStart>{$this->comment[0]})
            |(?<Juice_Closing>{$closing})
            {$expression_tokens}
            |(?<NewLine>[\n])
            |(?<Html_Space>[\t ]+)
            |(?<Html_Text>.+)
            /xsA"
        ;
    }

    public function tokens(): array
    {
        return $this->tokens;
    }
}

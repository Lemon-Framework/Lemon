<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

/**
 * Stores tag syntax for Juice.
 */
final class Syntax
{

    private array $tokens = [
        ['OpenningBracket', '('],
        ['ClosingBracket', ')'], 
        ['OpenningSquareBracket', '['],
        ['ClosingSquareBracket', ']'],
        ['DoubleArrow', '=>'],
        ['QuestionMark', '?'],
        ['Colon', ':'],
        ['Comma', ','],
        ['Fn', 'fn'],
        ['StringSymbol', '"|\''],
        ['Number', '(-?\d+(\.\d+)?)'],
        ['Variable', '\$([a-zA-Z][a-zA-Z0-9]+)'],
        ['Name', '[a-zA-Z][a-zA-Z0-9]+'],
    ];


    public readonly string $re;

    public readonly Operators $operators;

    /**
     * 
     */
    public function __construct(
        public readonly array $directive = ['\{#\s*(?&DIRECTIVE_NAME)', '#\}'],
        public readonly array $end = ['\{(\/|end)', '\}'],
        public readonly array $output = ['\{', '\}'], 
        public readonly array $unsafe = ['\{!', '!\}'], 
        public readonly array $comment = ['\{\-\-', '\-\-\}'],
        public readonly string $escape = '\\',
        Operators $operators = null,
    ) {
        $this->operators = $operators ?? new Operators();
        $this->tokens[] = ['BinaryOperator', $operators->buildBinaryRe()];
        $this->tokens[] = ['UnaryOperator', $operators->buildUnaryRe()];
        $this->re = $this->buildRe();
    }

    private function buildRe(): string
    {
        $escape = "(?<!{$this->escape})";
        // @todo dont forget about colision w syntax
        $closing = "{$this->directive[1]}|{$this->end[1]}|{$this->output[1]}|{$this->unsafe[1]}|{$this->comment[1]}";

        $expression_tokens = '';

        foreach ($this->tokens as [$name, $re]) {
            $expression_tokens .= "|(?<$name>$re)";
        }

        return "~
            (?(DEFINE)(?<DIRECTIVE_NAME>[:alpha:][:alnum:]+))
            (?<HtmlStart>\<)
            |(?<HtmlEnd>\>)
            |(?<HtmlClose>\</)
            |(?<StringDelim>\"|')
            |(?<DirectiveStart>{$escape}{$this->directive[0]})
            |(?<EndDirectiveStart>{$escape}{$this->end[0]})
            |(?<OutputStart>{$escape}{$this->output[0]})
            |(?<UnsafeStart>{$escape}{$this->unsafe[0]})
            |(?<CommentStart>{$escape}{$this->comment[0]})
            |(?<Closing>{$closing})
            {$expression_tokens}
            |(?<Space>\s+)
            |(?<Text>.+)
            ~xsA"
        ;
    }
}

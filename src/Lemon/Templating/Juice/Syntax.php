<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Templating\Juice\Token\HtmlTokenKind;
use Lemon\Templating\Juice\Token\JuiceTokenKind;
use Lemon\Templating\Juice\Token\PHPTokenKind;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Templating\Juice\Token\TokenKind;


/**
 * Stores tag syntax for Juice.
 */
final class Syntax
{

    private array $tokens = [
        ['OpenningBracket', '\('],
        ['ClosingBracket', '\)'], 
        ['OpenningSquareBracket', '\['],
        ['ClosingSquareBracket', '\]'],
        ['DoubleArrow', '=\>'],
        ['QuestionMark', '\?'],
        ['Colon', ':'],
        ['Comma', ','],
        ['Fn', 'fn'],
        ['Number', '(-?\d+(\.\d+)?)'],
        ['Variable', '\$([a-zA-Z][a-zA-Z0-9]+)'],
        ['Name', '[a-zA-Z][a-zA-Z0-9]+'],
    ];


    public readonly string $re;

    public readonly Operators $operators;

    /**
     * Describes core syntax of juice
     *
     * @param array{string, string} $directive Describes tokens of directives
     * @param array{string, string} $end Describes tokens of directive ending tag
     * @param array{string, string} $output Describes tokens of output tag
     * @param array{string, string} $unsafe Describes tokens of unescaped output tag
     * @param array{string, string} $comment Describes tokens of comment tag
     * @param string $escape Escape token that is used to ignore parsing
     */
    public function __construct(
        public readonly array $directive = ['\[\s*(?&DIRECTIVE_NAME)', '\]'],
        public readonly array $end = ['\[(\/|end)', '\]'],
        public readonly array $output = ['\[\[', '\]\]'], 
        public readonly array $unsafe = ['\[!', '!\]'], 
        public readonly array $comment = ['\[\-\-', '\-\-\]'],
        public readonly string $escape = '@',
        Operators $operators = null,
    ) {
        $this->operators = $operators ?? new Operators();
        $this->tokens[] = ['BinaryOperator', $this->operators->buildBinaryRe()];
        $this->tokens[] = ['UnaryOperator', $this->operators->buildUnaryRe()];
        $this->re = $this->buildRe();
    }

    private function buildRe(): string
    {
        // @todo dont forget about colision w syntax
        $closing = "({$this->directive[1]}|{$this->end[1]}|{$this->output[1]}|{$this->unsafe[1]}|{$this->comment[1]})";

        $expression_tokens = '';

        foreach ($this->tokens as [$name, $re]) {
            $expression_tokens .= "|(?<$name>$re)";
        }

        return "/
            (?(DEFINE)(?<DIRECTIVE_NAME>[a-zA-Z][a-zA-Z0-9]+))
            (?<HtmlTagOpen>\<)
            |(?<HtmlTagClose>\>)
            |(?<HtmlEndTag>\<\/)
            |(?<HtmlCommentOpen>\<!\-\-)
            |(?<HtmlCommentClose>\-\-\>)
            |(?<StringDelim>\"|')
            |(?<Escape>{$this->escape})
            |(?<DirectiveStart>{$this->directive[0]})
            |(?<EndDirectiveStart>{$this->end[0]})
            |(?<OutputStart>{$this->output[0]})
            |(?<UnsafeStart>{$this->unsafe[0]})
            |(?<CommentStart>{$this->comment[0]})
            |(?<Closing>{$closing})
            {$expression_tokens}
            |(?<NewLine>[\n])
            |(?<Space>\s+)
            |(?<Text>.+)
            /xsA"
        ;
    }
}

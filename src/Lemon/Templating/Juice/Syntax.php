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
        ['Backslash', '\\\\'],
        ['Operator', '[\!\@\#\%\^\&\*\+\<\>\.\/\|\=\~\?\-\:]+'],
        ['QuestionMark', '\?'],
        ['Colon', ':'],
        ['Comma', ','],
        ['Fn', 'fn'],
        ['As', 'as'],
        ['In', 'in'],
        ['Instanceof', 'instanceof'],
        ['Number', '(-?\d+(\.\d+)?)'],
        ['Variable', '\$([a-zA-Z_][a-zA-Z0-9_]*)'],
        ['Name', '[a-zA-Z_][a-zA-Z0-9_]*'],
    ];


    public readonly string $htmlRe;

    public readonly string $htmlTagRe;

    public readonly string $juiceRe;

    public readonly string $juiceUnclosedRe;

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
        $this->htmlRe = $this->buildHtmlRe();
        $this->htmlTagRe = $this->buildHtmlTagRe();
        [$this->juiceRe, $this->juiceUnclosedRe] = $this->buildJuiceRe();
    }

    public function getRe(Context $context): string 
    {
        return match($context) {
            Context::Html => $this->htmlRe,
            Context::HtmlTag => $this->htmlTagRe,
            Context::Juice => $this->juiceRe,
            Context::JuiceUnclosed => $this->juiceUnclosedRe,
        };
    }


    private function buildHtmlRe(): string 
    {
        return $this->buildRe("
            (?<Html_EndTagOpen>\<\/)
            |(?<Html_TagOpen>\<)
            |(?<Html_TagClose>\>)
            |(?<Html_CommentOpen>\<!\-\-)
            |(?<Html_CommentClose>\-\-\>)
            |(?<Html_StringDelim>\"|')
            |(?<Html_Equals>=)           
            |(?<Juice_Escape>{$this->escape})
            |(?<Juice_DirectiveStart>{$this->directive[0]})
            |(?<Juice_EndDirective>{$this->end})
            |(?<Juice_OutputStart>{$this->output[0]})
            |(?<Juice_UnsafeStart>{$this->unsafe[0]})
            |(?<Juice_CommentStart>{$this->comment[0]})
        "); 
    }

    private function buildHtmlTagRe(): string 
    {
        return $this->buildRe("
            (?<Html_EndTagOpen>\<\/)
            |(?<Html_TagOpen>\<)
            |(?<Html_Name>[!a-zA-Z_]+)
            |(?<Html_TagClose>\>)
            |(?<Html_CommentOpen>\<!\-\-)
            |(?<Html_CommentClose>\-\-\>)
            |(?<Html_StringDelim>\"|')
            |(?<Html_Equals>=)    
            |(?<Juice_Escape>{$this->escape})
            |(?<Juice_DirectiveStart>{$this->directive[0]})
            |(?<Juice_EndDirective>{$this->end})
            |(?<Juice_OutputStart>{$this->output[0]})
            |(?<Juice_UnsafeStart>{$this->unsafe[0]})
            |(?<Juice_CommentStart>{$this->comment[0]})
        ");
    }

    private function buildJuiceRe(): array 
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

        $expression_tokens = trim($expression_tokens, '|');

        return [
            $this->buildRe("
                (?<Juice_Closing>{$closing})
                |{$expression_tokens}
                "
            ),
            $this->buildRe("
                {$expression_tokens}
                |(?<Juice_Closing>{$closing})
                "
            ),
        ];  
    }

    private function buildRe(string $re): string
    {

        $not_in_text = implode('', 
            array_map(fn($in) => trim($in, '\\')[0], 
                [$this->directive[0], $this->output[0], $this->end, $this->unsafe[0], $this->comment[0], $this->escape]
            )
        );
        return "/(?(DEFINE)(?<DIRECTIVE_NAME>[a-zA-Z][a-zA-Z0-9]+))
            {$re}
            |(?<NewLine>[\n])
            |(?<Html_Space>[\t ]+)
            |(?<Html_Text>[^<{$not_in_text}]+)
        /xsA";
    }

    public function tokens(): array
    {
        return $this->tokens;
    }
}

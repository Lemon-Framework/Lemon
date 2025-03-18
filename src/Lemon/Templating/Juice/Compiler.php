<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Templating\Compiler as CompilerContract;
use Lemon\Templating\Juice\EscapingContext;
use Lemon\Templating\Juice\Parser\Parser;

// TODO error handling
class Compiler implements CompilerContract
{
    public readonly Syntax $syntax;
    public readonly Directives $directives;
    public readonly Operators $operators;
    public readonly HtmlNodes $htmlNodes;

    public function __construct(
        public readonly Config $config, 
    ) {
        $this->syntax = $config->get('templating.juice.syntax');
        $this->directives = $config->get('templating.juice.directives');
        $this->operators = $config->get('templating.juice.operators');
        $this->htmlNodes = $config->get('templating.juice.html-nodes');
    }

    public function compile(string $template): string 
    {
        $lexer = new Lexer($this->syntax, $template);
        $parser = new Parser($lexer, $this->htmlNodes, $this->operators, $this->directives);
        return $parser->parse()
                      ->generate(
                          new SematicContext(EscapingContext::Html), 
                          new Generators($this->operators)
                      )
        ;
    }

    public function getExtension(): string 
    {
        return 'juice';
    }
}

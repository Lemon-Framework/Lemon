<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Templating\Compiler as CompilerInterface;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;

class Compiler implements CompilerInterface
{

    private Lexer $lexer;

    private OutputCompiler $output;

    private DirectiveCompiler $directives;

    public function __construct()
    {
        $this->lexer = new Lexer(new Syntax()); // TODO custom syntax
        $this->output = new OutputCompiler();
        $this->directives = new DirectiveCompiler();
    }

    public function compile(string $template): string
    {
        $lex = $this->lexer->lex($template);
        $parser = new Parser($lex, $this->output, $this->directives);
        return $parser->parse();        
    }
}

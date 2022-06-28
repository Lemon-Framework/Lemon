<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Config\Config;
use Lemon\Templating\Compiler as CompilerInterface;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;

/**
 * Compiles Juice Templates.
 */
class Compiler implements CompilerInterface
{
    public readonly DirectiveCompiler $directives;

    private Lexer $lexer;

    private OutputCompiler $output;

    public function __construct(Config $config)
    {
        $this->lexer = new Lexer($config->get('templating.juice.syntax'));
        $this->output = new OutputCompiler();
        $this->directives = new DirectiveCompiler();
    }

    /**
     * {@inheritdoc}
     */
    public function compile(string $template): string
    {
        $lex = $this->lexer->lex(
            str_replace("\r\n", "\n", $template)
        );
        $parser = new Parser($lex, $this->output, $this->directives);

        return $parser->parse();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension(): string
    {
        return 'juice';
    }

    /**
     * Adds directive compiler class.
     */
    public function addDirectiveCompiler(string $name, string $class): static
    {
        $this->directives->addDirectiveCompiler($name, $class);

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Kernel\Container;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Compilers\TagCompiler;
use Lemon\Templating\Juice\Exceptions\ParserException;

class Parser
{
    private array $stack = [];

    /**
     * Stream of all tokens
     *
     * @var \Lemon\Support\Types\Array_<Token> 
     */
    private Array_ $tokens;

    public function __construct(
        array $tokens, 
        private OutputCompiler $output,
        private TagCompiler $tags
    )
    {
        $this->tokens = new Array_($tokens);
    }

    public function parse(): string
    {
        $result = '';
        foreach ($this->tokens as $token) {
            switch ($token->kind) {
                case Token::TAG:
                    if ($this->tags->isClosable($token->context[0])) {
                        $this->stack[] = $token->context[0];
                    }
                    $result .= $this->tags->compile($token);
                    break;
                case Token::TAG_END:
                    $top = Arr::pop($this->stack);
                    if ($top !== $token->context[0]) {
                        throw new ParserException(''); // TODO line counting
                    }
                    $result .= $this->tags->compile($token);
                    break;
                case Token::OUTPUT:
                    $result .= $this->output->compileEcho($token->context, 0);
                    break;
                case Token::UNESCAPED:
                    $result .= $this->output->compileUnescaped($token->context);
                    break;
                case Token::TEXT:
                    // TODO context
                    $result .= $token->context;
                    break;
            } 
        }
        return $result;
    }
}

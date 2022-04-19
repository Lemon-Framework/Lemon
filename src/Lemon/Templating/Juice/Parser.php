<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Exceptions\ParserException;

final class Parser
{
    public const CONTEXT_HTML = 0;
    public const CONTEXT_JS = 1;
    public const CONTEXT_ATTRIBUTE = 2;
    public const CONTEXT_JS_ATTRIBUTE = 3;

    private int $context;

    private array $stack = [];

    /**
     * Stream of all tokens.
     *
     * @var \Lemon\Support\Types\Array_<Token>
     */
    private Array_ $tokens;

    public function __construct(
        array $tokens,
        private OutputCompiler $output,
        private DirectiveCompiler $directives
    ) {
        $this->tokens = new Array_($tokens);
    }

    public function parse(): string
    {
        $result = '';
        foreach ($this->tokens as $token) {
            switch ($token->kind) {
                case Token::TAG:
                    if ($this->directives->isClosable($token->content[0])) {
                        $this->stack[] = $token->content[0];
                    }
                    $result .= $this->directives->compileOpenning($token->content[0], $token->content[1], $this->stack);

                    break;

                case Token::TAG_END:
                    $top = Arr::pop($this->stack);
                    if ($top !== $token->content[0]) {
                        throw new ParserException(''); // TODO line counting
                    }
                    $result .= $this->directives->compileClosing($token->content);

                    break;

                case Token::OUTPUT:
                    $result .= $this->output->compileEcho($token->content, $this->context);

                    break;

                case Token::UNESCAPED:
                    $result .= $this->output->compileUnescaped($token->content);

                    break;

                case Token::TEXT:
                    $this->context = $this->resolveContext($token->content, $this->context);
                    $result .= $token->content;

                    break;
            }
        }

        return $result;
    }

    public static function resolveContext(string $target, int $context): int
    {
        preg_match_all('/(<script.*?>)|(<\/script>)/', $target, $matches);

        $matches = $matches[0];
    
        if (Arr::size($matches) > 0) {
            if (preg_match('/<script.*?>/', Arr::last($matches))) {
                return self::CONTEXT_JS;
            }

            if ('</script>' === $matches[0]
                && self::CONTEXT_JS === $context) {
                return self::CONTEXT_HTML;
            }
        }

        $target = preg_replace('/\\\(\'|")/', '', $target);

        if (preg_match('/<[^>]+?on\w+?=(\'[^\']*|"[^\"]*)$/', $target)) {
            return self::CONTEXT_JS_ATTRIBUTE;
        }

        if (preg_match('/([^\']*?\'|[^\"]*?\")/', $target)
            && Arr::has([self::CONTEXT_ATTRIBUTE, self::CONTEXT_JS_ATTRIBUTE], $context)) {
            return self::CONTEXT_HTML;
        }

        if (preg_match('/<[^>]+?(src|href|codebase|cite|action|longdesc|profile|usemap|cite|classid|data|usemap|icon|manifest|formaction|poster|srcset|archive|content)=(\'[^\']*|"[^\"]*)$/', $target)) {
            return self::CONTEXT_ATTRIBUTE;
        }

        return $context;
    }
}

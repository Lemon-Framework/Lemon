<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;
use Lemon\Support\Types\Str;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;

/**
 * Provides token parsing of Juice.
 */
final class Parser
{
    public const CONTEXT_HTML = 0;
    public const CONTEXT_JS = 1;
    public const CONTEXT_ATTRIBUTE = 2;
    public const CONTEXT_JS_ATTRIBUTE = 3;

    private int $context = self::CONTEXT_HTML;

    /**
     * Array of curently unclosed directives.
     *
     * @var array<string> = []
     */
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

    /**
     * Parses tokens into raw php.
     */
    public function parse(): string
    {
        $result = '';
        foreach ($this->tokens as $token) {
            $content = $token->content;

            switch ($token->kind) {
                case Token::TAG:
                    if ($this->directives->isClosable($content[0])) {
                        $this->stack[] = $content[0];
                    }
                    $result .= $this->directives->compileOpenning($token, $this->stack);

                    break;

                case Token::TAG_END:
                    $top = array_pop($this->stack);
                    if ($top !== $content) {
                        throw new CompilerException('Unexpected end of directive '.$content, $token->line);
                    }
                    $result .= $this->directives->compileClosing($content);

                    break;

                case Token::OUTPUT:
                    $result .= $this->output->compileEcho($content, $this->context);

                    break;

                case Token::UNESCAPED:
                    $result .= $this->output->compileUnescaped($content);

                    break;

                case Token::TEXT:
                    $this->context = self::resolveContext($content, $this->context);
                    $result .= Str::replace($content, '<?php', '&ltphp');  // :trollak:

                    break;
            }
        }

        $top = Arr::last($this->stack);
        if ($top) {
            throw new CompilerException('Unclosed '.$top, -1);
        }

        return $result;
    }

    /**
     * Resolves template output context.
     */
    public static function resolveContext(string $target, int $context): int
    {
        preg_match_all('/(<script.*?>)|(<\/script>)/', $target, $matches);

        $matches = $matches[0];
        $result = $context;

        if (Arr::size($matches) > 0) {
            if (preg_match('/<script.*?>/', Arr::last($matches))) {
                $result = self::CONTEXT_JS;
            }

            if ('</script>' === $matches[0]
                && self::CONTEXT_JS === $context) {
                $result = self::CONTEXT_HTML;
            }
        }

        $target = preg_replace('/\\\(\'|")/', '', $target);

        if (preg_match('/.+?on\w+?=(\'[^\']*|"[^\"]*)$/', $target)) {
            $result = self::CONTEXT_JS_ATTRIBUTE;
        }

        if (preg_match('/([^\']*?\'|[^\"]*?\")/', $target)
            && Arr::has([self::CONTEXT_ATTRIBUTE, self::CONTEXT_JS_ATTRIBUTE], $context)) {
            $result = self::CONTEXT_HTML;
        }

        if (preg_match('/.+?(src|href|codebase|cite|action|longdesc|profile|usemap|cite|classid|data|usemap|icon|manifest|formaction|poster|srcset|archive|content)=(\'[^\']*|"[^\"]*)$/', $target)) {
            $result = self::CONTEXT_ATTRIBUTE;
        }

        return $result;
    }
}

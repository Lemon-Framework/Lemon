<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Kernel\Container;
use Lemon\Support\Types\Arr;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Token;

/**
 * Provides directive compilation.
 */
final class DirectiveCompiler
{
    /**
     * Contains all default directives.
     */
    public const DEFAULTS = [
        'case' => Directives\CaseDirective::class,
        'else' => Directives\ElseDirective::class,
        'elseif' => Directives\ElseIfDirective::class,
        'for' => Directives\ForDirective::class,
        'foreach' => Directives\ForeachDirective::class,
        'if' => Directives\IfDirective::class,
        'switch' => Directives\SwitchDirective::class,
        'unless' => Directives\UnlessDirective::class,
        'while' => Directives\WhileDirective::class,
        'include' => Directives\IncludeDirective::class,
        'extends' => Directives\Layout\ExtendsDirective::class,
        'block' => Directives\Layout\BlockDirective::class,
        'yield' => Directives\Layout\YieldDirective::class,
        'csrf' => Directives\CsrfDirective::class,
    ];

    private Container $compilers;

    public function __construct()
    {
        $this->compilers = new Container();
        $this->loadDefaults();
    }

    /**
     * Returns compiler of given directive.
     */
    public function getDirectiveCompiler(string $directive): Directive
    {
        if (!$this->hasDirectiveCompiler($directive)) {
            throw new CompilerException('Unknown directive '.$directive);
        }

        return $this->compilers->get($directive);
    }

    /**
     * Adds new directive compiler.
     */
    public function addDirectiveCompiler(string $directive, string $class): self
    {
        if ($this->hasDirectiveCompiler($directive)) {
            throw new CompilerException('Directive '.$directive.' already exist.');
        }

        if (!in_array(Directive::class, class_implements($class))) {
            throw new CompilerException('Directive class '.$class.' does not implement '.Directive::class.' Interface');
        }

        $this->compilers->add($class);
        $this->compilers->alias($directive, $class);

        return $this;
    }

    /**
     * Determins whenever directive compiler exists.
     */
    public function hasDirectiveCompiler(string $directive): bool
    {
        return $this->compilers->hasAlias($directive);
    }

    /**
     * Determins whenever directive can be closed.
     */
    public function isClosable(string $directive): bool
    {
        return method_exists($this->getDirectiveCompiler($directive), 'compileClosing');
    }

    /**
     * Compiles openning part of directive.
     */
    public function compileOpenning(Token $token, array $stack): string
    {
        $class = $this->getDirectiveCompiler($token->content[0]);

        return trim($class->compileOpenning($token, $stack));
    }

    /**
     * Compiles closing part of directive.
     */
    public function compileClosing(string $directive): string
    {
        $class = $this->getDirectiveCompiler($directive);

        if (method_exists($class, 'compileClosing')) {
            // TODO rethink to avoid this
            return trim($class->compileClosing());
        }

        throw new CompilerException('Unable to close directive'.$directive);
    }

    /**
     * Loads default directives.
     */
    private function loadDefaults(): void
    {
        foreach (self::DEFAULTS as $directive => $class) {
            $this->addDirectiveCompiler($directive, $class);
        }
    }
}

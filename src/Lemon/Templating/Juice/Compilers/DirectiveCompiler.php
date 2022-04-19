<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Kernel\Container;
use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Exceptions\CompilerException;

// TODO solve problems with else and stuff
final class DirectiveCompiler
{
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
    ];

    private Container $compilers;

    public function __construct()
    {
        $this->compilers = new Container();
        $this->loadDefaults();
    }

    public function getDirectiveCompiler(string $directive): Directive
    {
        if (!$this->hasDirectiveCompiler($directive)) {
            throw new CompilerException('Unknown directive '.$directive);
        }

        return $this->compilers->get($directive);
    }

    public function addDirectiveCompiler(string $directive, string $class): self
    {
        if ($this->hasDirectiveCompiler($directive)) {
            throw  new CompilerException('Directive '.$directive.' already exist.');
        }

        if (!Arr::has(class_implements($class), Directive::class)) {
            throw new CompilerException('Directive class '.$class.' does not implement '.Directive::class.' Interface');
        } 

        $this->compilers->add($class);
        $this->compilers->alias($directive, $class);

        return $this;
    }

    public function hasDirectiveCompiler(string $directive): bool
    {
        return $this->compilers->hasAlias($directive);
    }

    public function isClosable(string $directive): bool
    {
        return method_exists($this->getDirectiveCompiler($directive), 'compileClosing');
    }

    public function compileOpenning(string $directive, string $context, array $stack): string
    {
        $class = $this->getDirectiveCompiler($directive);

        return '<?php '.trim($class->compileOpenning($context, $stack)).' ?>';
    }

    public function compileClosing(string $directive): string
    {
        $class = $this->getDirectiveCompiler($directive);

        return '<?php '.trim($class->compileClosing()).' ?>';
    }

    private function loadDefaults(): void
    {
        foreach (self::DEFAULTS as $directive => $class) {
            $this->addDirectiveCompiler($directive, $class);
        }
    }
}

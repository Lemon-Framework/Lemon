<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Kernel\Container;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Exceptions\CompilerException;

// TODO solve problems with else and stuff
final class DirectiveCompiler
{
    public const DEFAULTS = [
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
        $this->compilers->add($class);
        $this->compilers->alias($directive, $class);

        return $this;
    }

    public function hasDirectiveCompiler(string $directive): bool
    {
        return $this->compilers->has($directive);
    }

    public function isClosable(string $directive): bool
    {
        return $this->getDirectiveCompiler($directive)->hasClosing();
    }

    public function compileOpenning(string $directive, string $context, array $stack): string
    {
        $class = $this->getDirectiveCompiler($directive);

        return '<?php '.trim($class->compileOpenning($context, $stack)).' ?>';
    }

    public function compileClosing(string $directive): string
    {
        return '<?php end'.$directive.' ?>';
    }

    private function loadDefaults(): void
    {
        foreach (self::DEFAULTS as $directive => $class) {
            $this->addDirectiveCompiler($directive, $class);
        }
    }
}

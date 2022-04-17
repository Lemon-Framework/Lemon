<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Kernel\Container;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Exceptions\CompilerException;

// TODO solve problems with else and stuff 
final class TagCompiler
{
    private Container $compilers;

    public const DEFAULTS = [
    ];

    public function __construct()
    {
        $this->compilers = new Container();
        $this->loadDefaults();
    }

    private function loadDefaults(): void
    {
        foreach (self::DEFAULTS as $tag => $class) {
            $this->addTagCompiler($tag, $class);
        }
    }

    public function getTagCompiler(string $tag): Directive
    {
        if (! $this->hasTagCompiler($tag)) {
            throw new CompilerException('Unknown tag '.$tag);
        }

        return $this->compilers->get($tag);
    }

    public function addTagCompiler(string $tag, string $class): self
    {
        $this->compilers->add($class);
        $this->compilers->alias($tag, $class);
        return $this;
    }

    public function hasTagCompiler(string $tag): bool
    {
        return $this->compilers->has($tag);
    }

    public function isClosable(string $tag): bool
    {
        return method_exists($this->getTagCompiler($tag), 'compileClosing');
    }

    public function compileOpenning(string $tag, string $context, array $stack): string
    { 
        $class = $this->getTagCompiler($tag);

        return $class->compileOpenning($context, $stack);        
    }
    
    public function compileClosing(string $tag): string
    { 
        $class = $this->getTagCompiler($tag);

        return $class->compileClosing();        
    }

}

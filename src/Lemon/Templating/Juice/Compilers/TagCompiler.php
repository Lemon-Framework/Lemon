<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Kernel\Container;
use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Compilers\Tags\Tag;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

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

    public function getTagCompiler(string $tag): Tag
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

    public function compile(Token $token): string
    {
        if (!Arr::has([Token::TAG, Token::TAG_END], $token->kind)) {
            throw new CompilerException('Given token must be of kind TAG or TAG_END');
        }

        $class = $this->getTagCompiler($token->context);
        if ($token->kind === Token::TAG_END) {
            return $class->getClosing();
        }

        return $class->compileOpenning($token->context);
            
    }
}

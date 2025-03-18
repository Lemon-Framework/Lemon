<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Templating\Juice\Nodes\Directives\CaseDirective;
use Lemon\Templating\Juice\Nodes\Directives\WhileDirective;
use Lemon\Templating\Juice\Nodes\Directives\UnlessDirective;
use Lemon\Templating\Juice\Nodes\Directives\TranslationDirective;
use Lemon\Templating\Juice\Nodes\Directives\SwitchDirective;
use Lemon\Templating\Juice\Nodes\Directives\IncludeDirective;
use Lemon\Templating\Juice\Nodes\Directives\IfErrorDirective;
use Lemon\Templating\Juice\Nodes\Directives\IfDirective;
use Lemon\Templating\Juice\Nodes\Directives\ElseDirective;
use Lemon\Templating\Juice\Nodes\Directives\ElseIfDirective;
use Lemon\Templating\Juice\Nodes\Directives\EachDirective;
use Lemon\Templating\Juice\Nodes\Directives\CsrfDirective;
use Lemon\Templating\Juice\Nodes\PairDirective;

class Directives
{
    private array $directives = [
        'csrf' => [CsrfDirective::class, []],
        'each' => [EachDirective::class, []], 
        'foreach' => [EachDirective::class, []], 
        'if' => [IfDirective::class, ['else' => ElseDirective::class, 'elseif' => ElseIfDirective::class]], 
        'iferror' => [IfErrorDirective::class, []], 
        'ife' => [IfErrorDirective::class, []], 
//        'include' => [IncludeDirective::class, []],
        'switch' => [SwitchDirective::class, ['case' => CaseDirective::class]], 
        'text' => [TranslationDirective::class, []], 
        '_' => [TranslationDirective::class, []], 
        'unless' => [UnlessDirective::class, []],
        'while' => [WhileDirective::class, []], 
    ];

    private array $temporary = [];

    public function is(string $directive): bool 
    {
        return 
            isset($this->directives[$directive]) 
            || isset($this->temporary[$directive])
        ;
    }

    public function isPair(string $directive): bool
    {
        return is_subclass_of($this->getNodeClass($directive), PairDirective::class);
    }

    public function getNodeClass(string $directive): string 
    {
        return 
            $this->directives[$directive][0] 
            ?? $this->temporary[$directive]
        ;
    }

    public function getDirectiveSpecific(string $directive): array 
    {
        return $this->directives[$directive][1];
    }

    public function addDirective(string $class): self 
    {
        return $this;
    }

    public function addTemporary(array $tmp): self 
    {
        $this->temporary = $tmp;
        return $this;
    }

    public function clearTemporary(): self 
    {
        $this->temporary = [];
        return $this;
    }
}

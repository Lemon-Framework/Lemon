<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Http\Request;

class Route
{
    private string $path;

    private string $patern = 'a-zA-Z_-0-9';

    public function __construct(
        string $path,
        private array $actions
    ) {
        $this->path = trim($path, '/');
    }

    public function action(string $method, callable $action): static
    {
        $this->actions[$method] = $action;

        return $this;
    }

    public function patern(string $patern): static
    {
        $this->patern = $patern;

        return $this;
    }

    public function matches(Request $request): ?array
    {
        $patern = $this->buildRegex();

        if ($patern == $this->path) {
            return $request->uri == $this->path ? [] : null;
        }

        return preg_match('/^'.$patern.'$/', $request->uri, $matches) ? $matches : null;
    }

    private function buildRegex(): string
    {
        return preg_replace('/{([a-zA-Z_0-9]+)}/', '(?<$1>['.$this->patern.']+)', $this->path);
    }
}

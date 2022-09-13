<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

class Lexer
{
    public function lex(string $template): array
    {

    }

    public function lexTag(string $template): ?array
    {
        if ($template[0] !== '<') {
            return null;
        }

        $name = '';
        $index = 1;
        while ($template[$index] !== ' ') {
            $name .= $template[$index];
        }

        if (strlen($name) === 0) {
            return null;
        }

        $next = substr($template, $index);

        $attributes = $this->lexAttributes($next);
        if (is_null($attributes)) {
            return null;
        }

        [$attributes, $next] = $attributes;
        $body = $this->lex($next);


    } 

    public function lexEndTag(string $template): ?array
    {

    }

    public function lexComment(string $template): Result
    {

    }

    public function lexAttributes(string $template): Result
    {

    }

    public function lexOutput(string $template): Result
    {

    }

    public function lexUnsafe(string $template): Result
    {
    
    }

    public function lexDirective(string $template): Result
    {

    }
} 

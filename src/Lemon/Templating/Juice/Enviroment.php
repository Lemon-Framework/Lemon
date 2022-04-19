<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Types\Str;

final class Enviroment
{
    public function escapeHtml(string $content): string
    {
        return htmlspecialchars($content);
    }

    public function escapeScript(string $content): string
    {
        return json_encode($content);
    }

    public function escapeAttribute(string $content): string
    {
        return Str::startsWith($content, 'javascript:') ? '' : $content;
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Support\Macros;

/**
 * Runtime string manipulaton utility.
 * TODO filters.
 */
final class Environment
{
    use Macros;

    /**
     * Escapes html entities.
     */
    public function escapeHtml(mixed $content): string
    {
        return htmlspecialchars((string) $content);
    }

    /**
     * Escapes data into javascript-ready.
     */
    public function escapeScript(mixed $content): string
    {
        return json_encode($content);
    }

    /**
     * Disables javascript in url attributes.
     */
    public function escapeAttribute(mixed $content): string
    {
        return str_starts_with((string) $content, 'javascript:') ? '' : htmlspecialchars((string) $content);
    }
}

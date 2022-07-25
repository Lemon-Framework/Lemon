<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

/**
 * Response with no body focused on redirecting.
 */
class RedirectResponse extends Response
{
    public function parseBody(): string
    {
        return '';
    }
}

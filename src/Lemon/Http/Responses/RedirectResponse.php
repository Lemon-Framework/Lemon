<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class RedirectResponse extends Response
{
    public function parseBody(): string
    {
        return '';
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

/**
 * Response with custom body.
 */
class CustomResponse extends Response
{
    public function parseBody(): string
    {
        return $this->body;
    }
}

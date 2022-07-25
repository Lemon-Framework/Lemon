<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

/**
 * Reponse with plain text body
 */
class TextResponse extends Response
{
    public function parseBody(): string
    {
        $this->header('Content-Type', 'text/plain');

        return $this->body;
    }
}

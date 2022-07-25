<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

/**
 * Response with html body
 */
class HtmlResponse extends Response
{
    public function parseBody(): string
    {
        $this->header('Content-Type', 'text/html');

        return $this->body;
    }
}

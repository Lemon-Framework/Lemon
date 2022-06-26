<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class HtmlResponse extends Response
{
    public function parseBody(): string
    {
        $this->header('Content-Type', 'text/html');

        return $this->body;
    }
}

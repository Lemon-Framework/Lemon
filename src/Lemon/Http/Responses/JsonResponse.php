<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class JsonResponse extends Response
{
    public function parseBody(): string
    {
        $this->header('Content-Type', 'application/json');

        return json_decode($this->body);
    }
}

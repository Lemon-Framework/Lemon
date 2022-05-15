<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class JsonResponse extends Response
{
    protected function handleBody(): void
    {
        header('Content-Type: application/json');
        echo json_decode($this->body);
    }
}

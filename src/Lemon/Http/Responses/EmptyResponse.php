<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class EmptyResponse extends Response
{
    public function handleBody(): void
    {
    }
}

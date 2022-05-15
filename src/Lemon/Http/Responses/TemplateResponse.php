<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

class TemplateResponse extends Response
{
    protected function handleBody(): void
    {
        header('Content-Type: text/html');
        $this->body->render();
    }
}

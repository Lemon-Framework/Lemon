<?php

declare(strict_types=1);

namespace Lemon\Http\Responses;

use Lemon\Http\Response;

/**
 * Response with html body from rendered template
 */
class TemplateResponse extends Response
{
    public function parseBody(): string
    {
        $this->header('Content-Type', 'text/html');

        ob_start();
        $this->body->render();

        return ob_get_clean();
    }
}

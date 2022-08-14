<?php

declare(strict_types=1);

namespace Lemon\Protection\Middlwares;

use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Protection\Csrf as ProtectionCsrf;

class Csrf
{
    public function handle(Request $request, ProtectionCsrf $csrf, ResponseFactory $response)
    {
        if ('GET' === $request->method) {
            return (new EmptyResponse())->cookie('CSRF_TOKEN', $csrf->getToken());
        }

        $cookie = $request->getCookie('CSRF_TOKEN');
        if ($cookie !== $request->get('CSRF_TOKEN') || null === $cookie) {
            return $response->error(400);
        }
    }
}

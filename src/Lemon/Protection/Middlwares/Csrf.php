<?php

declare(strict_types=1);

namespace Lemon\Protection\Middlwares;

use Lemon\Http\Request;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Protection\Csrf as ProtectionCsrf;
use Lemon\Support\Types\Arr;

class Csrf
{
    public function handle(Request $request, ProtectionCsrf $csrf, ResponseFactory $response)
    {
        if ('GET' === $request->method) {
            return (new EmptyResponse())->cookie('CSRF_TOKEN', $csrf->getToken());
        }

        if (!Arr::has(['POST', 'PUT'], $request->method)) {
            return;
        }

        $cookie = $request->getCookie('CSRF_TOKEN');
        if ($cookie !== $request->get('CSRF_TOKEN') || $cookie === null) {
            return $response->error(400);
        }

    }
}

<?php

declare(strict_types=1);

namespace Lemon\Protection\Middlwares;

use Lemon\Contracts\Http\ResponseFactory;
use Lemon\Contracts\Protection\Csrf as ProtectionCsrf;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Routing\Attributes\AfterAction;

class Csrf
{
    #[AfterAction()]
    public function handle(Request $request, ProtectionCsrf $csrf, ResponseFactory $responseFactory, Response $response)
    {
        if ('GET' != $request->method) {
            $cookie = $request->getCookie('CSRF_TOKEN');
            if ($cookie !== $request->get('CSRF_TOKEN') || null === $cookie) {
                return $responseFactory->error(400);
            }
        }

        return $response->cookie('CSRF_TOKEN', $csrf->getToken());
    }
}

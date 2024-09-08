<?php

declare(strict_types=1);

namespace Lemon\Protection\Middlwares;

use Lemon\Contracts\Http\CookieJar;
use Lemon\Contracts\Http\ResponseFactory;
use Lemon\Contracts\Protection\Csrf as ProtectionCsrf;
use Lemon\Http\Request;

class Csrf
{
    public function handle(Request $request, ProtectionCsrf $csrf, ResponseFactory $responseFactory, CookieJar $cookies)
    {
        if ('GET' != $request->method) {
            $cookie = $request->getCookie('CSRF_TOKEN');
            if ($cookie !== $request->get('CSRF_TOKEN') || null === $cookie) {
                return $responseFactory->error(400);
            }
        }

        $cookies->set('CSRF_TOKEN', $csrf->getToken(), 0, 'Strict');
    }
}

<?php

declare(strict_types=1);

namespace Lemon\Contracts\Http;

use Lemon\Http\Response;

interface ResponseFactory
{
    /**
     * Creates new response out of given callable.
     */
    public function make(callable $action, array $params = []): Response;

    /**
     * Returns response depending on given data.
     */
    public function resolve(mixed $data): Response;

    /**
     * Returns response for 400-500 http status codes.
     */
    public function error(int $code): Response;

    /**
     * Registers custom handler for given status code.
     */
    public function handle(int $code, callable $action): static;
}

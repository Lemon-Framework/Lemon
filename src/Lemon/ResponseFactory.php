<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Response Zest
 * Provides static layer over the Lemon Response Factory.
 *
 * @method static \Lemon\Http\Response error(int $code)                           Returns response of 400-500 http status codes.
 * @method static \Lemon\Http\Response make(callable $action, array $params = []) Creates new response out of given callable
 * @method static \Lemon\Http\Response resolve(mixed $data)                       Returns response depending on given data
 * @method static \Lemon\Http\Response error(int $code)                           Returns response for 400-500 http status codes.
 * @method static \Lemon\Http\Response raise(int $code)                           Returns response for 400-500 http status codes.
 * @method static static               handle(int $code, callable $action)        Registers custom handler for given status code
 *
 * @see \Lemon\Http\ResponseFactory
 */
class ResponseFactory extends Zest
{
    public static function unit(): string
    {
        return 'response';
    }
}

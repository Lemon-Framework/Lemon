<?php

declare(strict_types=1);

// --- This file contains helping functions for whole framework. ---

use Lemon\Config;
use Lemon\Debug;
use Lemon\Env;
use Lemon\Http\Response;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\ResponseFactory;
use Lemon\Support\Pipe;
use Lemon\Templating\Template;

if (!function_exists('arr')) {
    /**
     * Creates new array.
     *
     * Instead of
     * [
     *     "foo" => 10
     * ]
     *
     * you can use
     * arr(
     *     foo: 10
     * )
     */
    function arr(mixed ...$data): array
    {
        return $data;
    }
}

if (!function_exists('compose')) {
    /**
     * Composes 2 functions into one like haskells .
     * Most useless function but ok.
     */
    function compose(callable $first, callable $second): callable
    {
        return fn ($value) => $first($second($value));
    }
}

if (!function_exists('config')) {
    /**
     * Gets/Sets value to config or returns config service.
     */
    function config(string $key = null, mixed $value = null): mixed
    {
        if (!$key) {
            return Config::getAccessor();
        }

        if ($value) {
            return Config::set($key, $value);
        }

        return Config::get($key);
    }
}

if (!function_exists('d')) {
    /**
     * Dumps values.
     */
    function d(mixed ...$values): void
    {
        foreach ($values as $value) {
            Debug::dump($value);
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Dumps values and exits app.
     */
    function dd(mixed ...$values): void
    {
        foreach ($values as $value) {
            Debug::dump($value);
        }

        exit;
    }
}

if (!function_exists('env')) {
    /**
     * Gets value from env or returns env service.
     */
    function env(string $key = null, string $value = null): mixed
    {
        if (!$key) {
            return Env::getAccessor();
        }

        return Env::get($key, $value);
    }
}

if (!function_exists('error')) {
    /**
     * Returns response with given status code.
     */
    function error(int $code): Response
    {
        return Lemon\ResponseFactory::error($code);
    }
}

if (!function_exists('is_user_podvodnik')) {
    /**
     * Returns whenever is user podvodnik using sofisticated algorithms.
     */
    function is_user_podvodnik(string $name): bool
    {
        return 'CoolFido' === $name;
    }
}

if (!function_exists('pipe')) {
    /**
     * Returns new Pipe.
     */
    function pipe(mixed $value): Pipe
    {
        return Pipe::send($value);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirects user to given adress.
     */
    function redirect(string $location): RedirectResponse
    {
        return (new RedirectResponse())->location($location);
    }
}

if (!function_exists('response')) {
    /**
     * Returns response with given data.
     */
    function response(mixed $data): Response
    {
        return ResponseFactory::resolve($data);
    }
}

if (!function_exists('template')) {
    /**
     * Returns template with given name and data.
     */
    function template(string $template, mixed ...$data): Template
    {
        return \Lemon\Template::make($template, $data);
    }
}

if (!function_exists('text')) {
    /**
     * Returns text of given key.
     */
    function text(string $key): string
    {
        return \Lemon\Translator::text($key);
    }
}

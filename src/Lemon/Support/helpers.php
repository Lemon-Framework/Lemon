<?php

declare(strict_types=1);

// --- This file contains helping functions for whole framework. ---

use Lemon\Config;
use Lemon\Debug;
use Lemon\Env;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Support\Pipe;
use Lemon\Support\Types\Array_;
use Lemon\Templating\Template;

if (!function_exists('arr')) {
    /**
     * Creates new Array_.
     */
    function arr(mixed ...$data): Array_
    {
        return new Array_($data);
    }
}

if (!function_exists('config')) {
    /**
     * Gets/Sets value to config or returns config service.
     */
    function config(string $key = null, string $value = null): mixed
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

if (!function_exists('template')) {
    /**
     * Returns template with given name and data.
     */
    function template(string $name, mixed ...$data): Template
    {
        return \Lemon\Template::make($name, $data);
    }
}

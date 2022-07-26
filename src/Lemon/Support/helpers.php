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

function template(string $name, mixed ...$data): Template
{
    return \Lemon\Template::make($name, $data);
}

function redirect(string $location): RedirectResponse
{
    return (new RedirectResponse())->location($location);
}

function arr(mixed ...$data): Array_
{
    return new Array_($data);
}

function pipe(mixed $value): Pipe
{
    return Pipe::send($value);
}

function env(string $key = null, string $value = null): mixed
{
    if (!$key) {
        return Env::getAccessor();
    }

    return Env::get($key, $value);
}

function config(string $key, string $value): mixed
{
    if (!$key) {
        return Config::getAccessor();
    }

    if ($value) {
        return Config::set($key, $value);
    }

    return Config::get($key);
}

function d(mixed ...$values): void
{
    foreach ($values as $value) {
        Debug::dump($value);
    }
}

function dd(mixed ...$values): void
{
    foreach ($values as $value) {
        Debug::dump($value);
    }

    die();
}

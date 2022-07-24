<?php

declare(strict_types=1);

// --- This file contains helping functions for whole framework. ---

use Lemon\Http\Responses\RedirectResponse;
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

function pipe()
{

}

function env()
{

}

function config()
{

}



<?php

declare(strict_types=1);

// --- This file contains helping functions for whole framework. ---

use Lemon\Config;
use Lemon\Env;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Support\Pipe;
use Lemon\Support\Types\Array_;
use Lemon\Templating\Template;

if(!function_exists('template')){
function template(string $name, mixed ...$data): Template
{
    return \Lemon\Template::make($name, $data);

}}

if(!function_exists('redirect')){
function redirect(string $location): RedirectResponse
{
    return (new RedirectResponse())->location($location);

}}

if(!function_exists('arr')){
function arr(mixed ...$data): Array_
{
    return new Array_($data);

}}

if(!function_exists('pipe')){
function pipe(mixed $value): Pipe
{
    return Pipe::send($value);

}}

if(!function_exists('env')){
function env(string $key = null, string $value = null): mixed
{
    if (!$key) {
        return Env::getAccessor();
    }

    return Env::get($key, $value);

}}

if(!function_exists('config')){
function config(string $key, string $value): mixed
{
    if (!$key) {
        return Config::getAccessor();
    }

    if ($value) {
        return Config::set($key, $value);
    }

    return Config::get($key);

}}

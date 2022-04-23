<?php

declare(strict_types=1);

namespace Lemon\Debug;

final class Style
{
    public function __construct(
        private int $background = 0x282828,
        private int $text = 0xEBDBB2,
        private int $array_key = 0xD79921,
        private int $property = 0x458588,
        private int $string = 0x98971A,
        private int $number = 0xB16286,
        private int $bool = 0xB16286,
        private int $null = 0xD79921
    ) {
    }

    public function generate(): string
    {
        return preg_replace_callback('/\{{(.+?)\}}/', function ($matches) {
            return dechex($this->{$matches[1]});
        }, file_get_contents(__DIR__.'/stubs/style.html.stub')); // TODO stub generator, caching?
    }
}

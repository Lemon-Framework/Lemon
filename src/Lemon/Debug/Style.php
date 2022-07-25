<?php

declare(strict_types=1);

namespace Lemon\Debug;

final class Style
{
    public function __construct(
        private string $background = '282828',
        private string $text = 'ebdbb2',
        private string $font_url = 'https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@300&display=swap',
        private string $font_name = 'Source Code Pro',
        private string $font_type = 'monospace',
        private string $array_key = 'd79921',
        private string $property = '458588',
        private string $string = '98971a',
        private string $number = 'b16286',
        private string $bool = 'b16286',
        private string $null = 'd79921'
    ) {
    }

    /**
     * Generates css from style properties.
     */
    public function generate(): string
    {
        return preg_replace_callback('/\{{(.+?)\}}/', function ($matches) {
            return $this->{$matches[1]};
        }, file_get_contents(__DIR__.'/stubs/style.html.stub')); // TODO stub generator, caching?
    }
}

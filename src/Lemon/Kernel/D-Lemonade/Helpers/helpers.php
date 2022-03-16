<?php

declare(strict_types=1);

// Formats text with color
function textFormat($text, $color)
{
    return "\033[{$color}m{$text}\033[0m";
}

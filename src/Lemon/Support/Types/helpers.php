<?php

use Lemon\Support\Types\Array_;

if (!function_exists('arr')) {
    /**
     * Creates new Array_ object with given items
     *
     * @param
     */
    function arr(...$items)
    {
        return new Array_($items);
    }
}

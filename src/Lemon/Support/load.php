<?php

declare(strict_types=1);

if (! function_exists('load')) {
    function load($file): void
    {
        $file_content = file_get_contents($file);
        $result = $file_content;
        $result = preg_replace_callback("/(\"[^\"]*\")|('[^']*')/", static function ($matches) {
            return '\\Lemon\\Support\\Types\\String_::from('.$matches[0].')';
        }, $result);
        echo $result;
    }
}

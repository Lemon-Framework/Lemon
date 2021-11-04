<?php

$contains_style = false;

function parse($target)
{
    $result = "";
    if (gettype($target) == "object")
        return parseObject($target);

    foreach ($target as $key => $value)
    {
        if (is_array($value))
        {
            $parsed = parse($value);
            $size = sizeof($value);
            $result.= "<details><summary class=\"array-key\">$key => Array:$size</summary><div>$parsed</div></details>";
            continue;
        }
        if (gettype($value) == "object")
            $value = parseObject($value);
        
        $value = $value ?: "undefined";
        $result .= "<span class=\"key\">[$key]</span>=><span class=\"value\">$value</span><br>";
            
    }
    return $result;
}

function parseObject($target)
{
    $original_class = get_class($target);
    $class_vars = get_class_vars($original_class);
    $class_methods = get_class_methods($original_class);

    $class_content = ["variables" => $class_vars, "methods" => $class_methods];
    $parsed = parse($class_content);

    $result = "<details><summary class=\"class\">Class $original_class</summary><div>$parsed</div></details>";

    return $result;
}

function dump(...$targets) 
{
    $parsed = "";
    foreach ($targets as $target)
    {
        if (is_array($target))
        {
            $parsed_data = parse($target);
            $size = sizeof($target);
            $parsed .= "<div class=\"bg\"><details><summary class=\"array-key\">Array:$size</summary><div>$parsed_data</div></details></div>";
            continue;
        }

        if (!in_array(gettype($target), ["string", "int", "bool"]))
        {
            $parsed .= "<div class=\"bg\">" . parse($target) . "</div>";
            continue;
        }

        $parsed .= "<div class=\"bg\">" . $target . "</div>";
    }

    global $contains_style;
    if (!$contains_style)
    {
        $contains_style = true;
        echo "<style>@import url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@300&display=swap');* {outline: none}.bg {background-color: #282c34; color: #abb2bf;font-family: 'Source Code Pro', monospace; font-size: 15pt;padding: 1%; margin: 0;}.array-key {color: #56b6c2;}.key {margin-right: 2%;margin-left: 2%;color: #98c379;}.value {margin-left: 2%; color: #c678dd;}.class {color: #e0af68;}div {margin-left: 2%;}</style>";
    }
    echo $parsed;
}

function dd(...$targets)
{
    dump(...$targets);
    die();
}

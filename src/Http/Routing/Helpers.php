<?php

/**
 *
 * Returns callback parameter types
 *
 * @param Closure|String $callback|$function_name
 *
 * @return Array
 *
 * */
function getParamTypes($callback)
{
    $types = [];

    $reflected = new ReflectionFunction($callback);
    $params = $reflected->getParameters();
    foreach ($params as $param)
    {
        $type = $param->getType();
        if (!$type)
            $type = "mixed";
        $types[array_search($param, $params)] = gettype($type) == "string" ? $type : $type->__toString();
    }

    return $types;

}

?>

<?php

if (!function_exists("getParamTypes")) {
    /**
     * Returns callback parameter types
     *
     * @param Closure|String|Array $callback|$function_name
     *
     * @return Array
     */
    function getParamTypes($callback)
    {
        $types = [];

        if (is_array($callback)) {
            $reflected = new ReflectionMethod($callback[0], $callback[1]);
        } else {
            $reflected = new ReflectionFunction($callback);
        }
        $params = $reflected->getParameters();
        foreach ($params as $param) {
            $type = $param->getType();
            if (!$type) {
                $type = "mixed";
            }
            $types[array_search($param, $params)] = (string) $type;
        }

        return $types;
    }
}

<?php

namespace Lemon\Support\Utils;

use Exception;

/**
 * .env managing utility
 */
class Env
{
    /** .env location */
    public static $path;
    
    /**
     * Sets .env location
     * 
     * @param String $path
     */
    public static function setPath(String $path)
    {
        self::$path = $path . "/.env";
    }

    /**
     * Returns value saved by given key
     * 
     * @param String $key
     * @return String
     */
    public static function get(String $key)
    {
        $data = self::all();
        if (!isset($data[$key]))
            throw new Exception("Env key $key does not exist!");
        return $data[$key];
    }

    /**
     * Sets value for given key
     *
     * @param String $key
     * @param mixed $value
     */
    public static function set(String $key, $value)
    {
        if (!is_string($value))
            throw new Exception("Value can't be converted to string!");

        $data = self::all();
        $data[$key] = (String)$value;

        self::replace($data);
    }

    /**
     * Sets whole .env file to given Array
     * 
     * @param Array<String, String> $data
     */
    public static function replace(Array $data)
    {
        $result = "";
        foreach ($data as $key => $value)
            $result .= "$key=$value" . PHP_EOL;

        $file = fopen(self::$path, "w");
        fwrite($file, $result);
        fclose($file);
    }
    
    /**
     * Clears whole .env file
     */
    public static function clear()
    {
        $file = fopen(self::$path, "w");
        fwrite($file, "");
        fclose($file);
    }
    
    /**
     * Removes given key with value
     * 
     * @param String $key
     */
    public static function remove(String $key)
    {
        $data = self::all();
        if (!isset($data[$key]))
            throw new Exception("Env key $key does not exist!");

        unset($data[$key]);
        self::replace($data);
    }
    
    /**
     * Returns .env file content
     *
     * @return Array<String, String>
     */
    public static function all()
    {
        $data = file_get_contents(self::$path);
        $result = [];

        foreach (explode(PHP_EOL, $data) as $line)
        {
            $pair = explode("=", $line);
            if (isset($pair[1]))
                $result[$pair[0]] = $pair[1];
        }
        return $result;
    }

}



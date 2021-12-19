<?php

namespace Lemon\Support\Types;

use Exception;

/**
 * TODO Docblocks
 */
class Array_
{

    public static function fromJson(String|String_ $subject)
    {
        return json_decode($subject, true);
    }

    public static function range(int $from, int $to, int $increment=1)
    {
        return new Array_(range($from, $to, $increment));
    }

    /** Array content */
    public $content;

    /** Arary lenght */
    public $lenght;

    public function __construct(Array $content=[])
    {
        $this->content = $content;
        $this->lenght = sizeof($content);
    } 
    
    public function __get($key)
    {
        if (!isset($this->content[$key]))
            throw new Exception("Undefined array key $key");
        return $this->content[$key];
    } 

    public function __set($key, $value)
    {
        $this->content[$key] = $value;
    }

    public function push(...$values)
    {
        array_push($this->content, ...$values);
        $this->lenght();
        return $this; 
    }

    public function pop()
    {
        array_pop($this->content);
        $this->lenght();
        return $this;
    }

    public function lenght()
    {
        $this->lenght = sizeof($this->content);
        return $this->lenght;
    }

    public function json()
    {
        return json_encode($this->content);
    }

    public function export()
    {
        $parsed_arrays = new Array_();
        foreach ($this->content as $array)
            $parsed_arrays->push($array instanceof Array_ ? $array->export() : $array);
        
        return $parsed_arrays->content;
    }

    public function chunk(int $lenght)
    {
        $this->content = array_chunk($this->content, $lenght);
        return $this;
    }

    public function hasKey($key) 
    {
        return array_key_exists($key, $this->content);
    }

    public function filter(callable $callback)
    {
        $this->content = array_filter($this->content, $callback);
        $this->lenght();
        return $this;
    }

    public function firstKey()
    {
        return array_key_first($this->content);
    }

    public function lastKey()
    {
        return array_key_last($this->content);
    }

    public function first()
    {
        return $this->content[$this->firstKey()];
    }

    public function last()
    {
        return $this->content[$this->lastKey()];
    }

    public function keys()
    {
        return new Array_(array_keys($this->content));
    }

    public function values()
    {
        return new Array_(array_values($this->content));
    }

    public function map(callable $callback)
    {
        $this->content = array_map($callback, $this->content);
        return $this;
    }

    public function merge(Array|Array_ ...$arrays)
    { 
        $arrays = (new Array_($arrays))->export();
        $this->content = array_merge($this->content, ...$arrays);
        $this->lenght();
        return $this; 
    } 

    public function random(int $count=1)
    {
        return array_rand($this->content, $count);
    }

    public function shuffle()
    {
        $this->content = shuffle($this->content);
        return $this;
    }

    public function replace(Array|Array_ ...$replacements)
    {
        $replacements = (new Array_($replacements))->export();
        $this->content = array_replace($this->content, ...$replacements);
        $this->lenght();
        return $this;
    }

    public function reverse()
    {
        $this->content = array_reverse($this->content);
        return $this;
    }

    public function slice(int $start, int $lenght)
    {
        $this->content = array_slice($this->content, $start, $lenght);
        return $this;
    }

    public function sum()
    {
        return array_sum($this->content);
    }

    public function contains($needle)
    {
        return in_array($needle, $this->content);
    }    
}

<?php

namespace Lemon\Exceptions\Handling;

use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;

class Consultant
{

    private string $message;

    public array $signatures = [
        'Call to undefined function (\w+?)\(\)' => 'function',
        'Call to undefined method (\w+?)::(\w+?)\(\)' => 'method',
        'Undefined property: (\w+?)::\$(\w+?)' => 'property',
        'Unexpected <\?(php|=) at line [0-9]+' => 'viewPHPTags',
        'View (.*?) does not exist or is not readable' => 'wrongViewName'
    ];

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function giveAdvice()
    {
    }

    public function findHandler()
    {
        foreach ($this->signatures as $signature => $method)
            if (preg_match('/^' . $signature . '$/', $this->message))
                return $method;
    }

    
    public function handleFunction($matches)
    {
        $functions = Arr::merge(get_defined_functions()['internal'], get_defined_functions()['user'])->content;
        $match = $this->bestMatch($functions, $matches[1]);

        return [  
            $match ? ('Did you mean ' . $match . '?') : 'Function was propably not loaded.',
        ];

    }

    public function bestMatch($haystack, $needle)
    {
        $best = 0;
        $best_value = '';
        foreach ($haystack as $item)
        {
            if (($distance = similar_text($item, $needle)) > $best)
            {
                $best = $distance;
                $best_value = $item;
            }
        }
        return $best_value;
    }
}

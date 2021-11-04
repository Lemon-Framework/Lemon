<?php

use Lemon\Kernel\Lemonade\Signature;

class Command 
{

    public $signature;

    public $action;

    public $description;

    public function __construct(String $signature, $action, $description="")
    {
        $this->signature = new Signature($signature);
        $this->action = $action;
        $this->description = $description;
    }

    /**
     * Validates command signature with given input
     *
     * @param String $input
     * @return Array|false
     */
    public function validate(String $input)
    {
        if ($arguments = $this->signature->matches($input))
            return $arguments;

        return false;
    }
    
    /**
     * Runs command with given arguments
     *
     * @param Array $arguments
     */
    public function run(Array $arguments)
    {
        call_user_func_array($this->action, $arguments);
    }

    public function info()
    {
        $name = $this->signature->name;
        $description = $this->description ? $this->description : "";        
        return ["name" => $name, "description" => $description];
    }
}

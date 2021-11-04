<?php

namespace Lemon\Kernel\Lemonade;

class Signature
{
    public $signature;

    public $name;

    public function __construct(String $signature)
    {
        $this->signature = $signature;
        $this->name = explode(" ", $signature)[0];
    }

    public function matches(String $input)
    {
        return explode(" ", $input)[0] == $this->name;
    }
}

$s = new Signature("make:parek {parek} {-rohlik} {-prasopes}");
echo $s->matches("make:parek rizek -prasopes -rohlik") ? "cs" : "asg";

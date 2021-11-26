<?php

use Lemon\Views\ViewCompiler;
use PHPUnit\Framework\TestCase;

abstract class ViewTestCase extends TestCase
{
    public function compieView($name, $template, $parameters)
    {
        $compiler = new ViewCompiler($name, $template, $parameters);
        return $compiler->compile();
    }
}

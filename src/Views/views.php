<?php
/*
 *
 * Lemon template system
 *
 * */

use Lemon\Views\ViewCompiler;

function view($name, $arguments = [])
{
    return ViewCompiler::compile($name, $arguments);
}

?>

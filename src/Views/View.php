<?php
/**
 * Templating system is inspired by the one that Mia made
 * https://github.com/VottusCode
 *
 */

namespace Lemon\Views;

class View
{
    
    /** Template name */
    public $name;

    /** Raw template content */
    public $raw_template;

    /** Compiled template content*/
    public $compiled_template;

    /** Template arguments */
    public $arguments;

    /**
     * Creates new Lemon Template instance
     *
     * @param String $name
     * @param Array $result
     * @param Array $args
     *
     */
    public function __construct(String $name, Array $result, Array $args = [])
    {
        $this->name = $name;
        $this->raw_template = $result["raw"];
        $this->compiled_template = $result["compiled"];
        $this->arguments = $args;
    }

}


?>

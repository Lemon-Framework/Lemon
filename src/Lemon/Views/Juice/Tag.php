<?php

namespace Lemon\Views\Juice;

class Tag
{
    public $patern;

    /**
     * Creates Juice tempalte Tag
     *
     * @param String $patern
     * @param String[] $replacement 
     *
     */
    public function __construct(String $patern, Array $replacement)
    {
        $this->patern = $patern;   
        $this->replacement = $replacement;
    }

    /**
     * Compiles tag in given template
     *
     * @param String $template
     * @return String
     *
     */
    public function compile(String $template)
    {
        $replacement = $this->replacement;
        $template = preg_replace_callback("/{$this->patern}/", function($matches) use($replacement) {
                return $replacement[0] . $matches[1] . $replacement[1];
        }, $template);
        return $template;
    }
}

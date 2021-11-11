<?php

namespace Lemon\Views;

class Tag
{
    /** Opening tags*/
    public $open_tag;

    /** Closing tags */
    public $close_tag;

    /**
     * Constructs whole Tag
     *
     * @param Array $open_tag
     * @param Array $close_tag
     *
     */
    public function __construct(Array $open_tag, Array $close_tag)
    {
        $this->open_tag = $open_tag;
        $this->close_tag = $close_tag;
    }

    /**
     * Compiles tag with content
     *
     * @param Array $matches
     *
     */
    public function compile(Array $matches)
    {
        return $this->open_tag[0] . $matches[1] . $this->close_tag[0];
    }

}



<?php

namespace Lemon\Debug\Handling\Attributes;

/**
 * Represents documentation section of given class`
 */
#[\Attribute()]
class Doc
{
    public function __construct(
        /**
         * Documentation section of given class
         */
        public readonly string $section,
    ) {

    }
}

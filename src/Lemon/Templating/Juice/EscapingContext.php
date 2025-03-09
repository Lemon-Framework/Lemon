<?php

namespace Lemon\Templating\Juice;

enum EscapingContext
{
    // tbd
    case Html;
    case Attribute;
    case Script;
    case ScriptAttribute;
}

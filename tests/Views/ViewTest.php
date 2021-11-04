<?php

use Lemon\Views\ViewCompiler;

test("basic template", function() {
    $template = "{% if (\$foo == \"bar\"): %}<h1>cau</h1>{% endif; %}";
    $vc = new ViewCompiler("foo", $template, ["foo"=>"bar"]);
    $view = $vc->compile();
    expect($view->resolve())->toBe("<h1>cau</h1>");
});

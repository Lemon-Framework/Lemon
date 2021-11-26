<?php

test("Safe view variable rendering", function() {
    $template = '<h1>{{ $foo }}</h1>';
    $result = '<h1><?= htmlentities( $foo ) ?></h1>';

    $view = $this->compileView("foo", $template);
    expect($view->compiled_template)->toBe($result);
});

test("Vulnerable view variable rendering", function() {
    $template = '<h1>{! $foo !}</h1>';
    $result = '<h1><?=  $foo  ?></h1>';

    $view = $this->compileView("foo", $template);
    expect($view->compiled_template)->toBe($result);
});

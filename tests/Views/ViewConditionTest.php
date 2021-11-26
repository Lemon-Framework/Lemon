<?php

test("Incomplete view conditions", function() {
    $template = '
{% if ($foo == "bar") %}
    <h1>baz</h1>
{% endif; %}
';
    $result = '
<?php  if ($foo == "bar")  ?>
    <h1>baz</h1>
<?php  endif;  ?>
';
    $view = $this->compieview('foo', $template, ['foo' => 'bar']);
    expect($view->compiled_template)->tobe($result);
});

test("Complete view conditions", function() {
    $template = '
{% if ($foo == "bar") %}
    <h1>Foo</h1>
{% else: %}
    <h1>Baz</h1>
{% endif; %}
';
    $result = '
<?php  if ($foo == "bar")  ?>
    <h1>Foo</h1>
<?php  else:  ?>
    <h1>Baz</h1>
<?php  endif;  ?>
';
    $view = $this->compieView('foo', $template, ['foo' => 'bar']);
    expect($view->compiled_template)->toBe($result);
});

test("Nested view conditions", function() {
    $template = '
{% if ($foo == "bar") %}
    {% if ($foo != "foo") %}
        <h1>baz</h1>
    {% endif; %}
{% endif; %}
';
    $result = '
<?php  if ($foo == "bar")  ?>
    <?php  if ($foo != "foo")  ?>
        <h1>baz</h1>
    <?php  endif;  ?>
<?php  endif;  ?>
';
    $view = $this->compieview('foo', $template, ['foo' => 'bar']);
    expect($view->compiled_template)->tobe($result);
});

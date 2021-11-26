<?php

test("View for loop", function() {
    $template = '
<ul>
    {% for ($i = 10; $i > 0; $i++) %}
        <li>$i</li>
    {% endfor; %}
</ul>
';
    $result = '
<ul>
    <?php  for ($i = 10; $i > 0; $i++)  ?>
        <li>$i</li>
    <?php  endfor;  ?>
</ul>
';
    $view = $this->compileView('foo', $template);
    expect($view->compiled_template)->tobe($result);
});

test("View foreach loop", function() {
    $template = '
<ul>
    {% foreach ($foo as $bar) %}
        <li>$bar</li>
    {% endforeach; %}
</ul>
';
    $result = '
<ul>
    <?php  foreach ($foo as $bar)  ?>
        <li>$bar</li>
    <?php  endforeach;  ?>
</ul>
';
    $view = $this->compileView('foo', $template);
    expect($view->compiled_template)->tobe($result);
});

test("View while loop", function() {
    $template = '
<ul>
    {% while (true) %}
        <li>Something</li>
    {% endwhile; %}
</ul>
';
    $result = '
<ul>
    <?php  while (true)  ?>
        <li>Something</li>
    <?php  endwhile;  ?>
</ul>
';
    $view = $this->compileView('foo', $template);
    expect($view->compiled_template)->tobe($result);
});

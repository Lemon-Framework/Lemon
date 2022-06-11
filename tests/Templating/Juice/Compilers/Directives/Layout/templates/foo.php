<?php $_layout = new \Lemon\Templating\Juice\Compilers\Directives\Layout\Layout(__DIR__.DIRECTORY_SEPARATOR.'bar.php'); ?>

<?php $_layout->block('foo', function() { ?>
    foo
<?php }) ?>

<?php $_layout->block('bar', function() { ?>
    bar
<?php }) ?>

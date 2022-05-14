<?php $_layout = new \Lemon\Templating\Juice\Compilers\Directives\Layout\Layout(__DIR__.DIRECTORY_SEPARATOR.'bar.php'); ?>

<?php $_layout->block('foo') ?>
    foo
<?php $_layout->endBlock() ?>

<?php $_layout->block('bar') ?>
    bar
<?php $_layout->endBlock() ?>

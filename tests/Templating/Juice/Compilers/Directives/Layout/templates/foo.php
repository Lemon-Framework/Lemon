<?php declare(strict_types=1);
$_layout = new \Lemon\Templating\Juice\Compilers\Directives\Layout\Layout(__DIR__.DIRECTORY_SEPARATOR.'bar.php'); ?>

<?php $_layout->startBlock('foo'); ?>
    <?php echo $foo; ?>
<?php $_layout->endBlock(); ?>

<?php $_layout->startBlock('bar'); ?>
    bar
<?php $_layout->endBlock(); ?>

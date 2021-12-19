<?php

use Lemon\Exceptions\ViewException;
use Lemon\Views\Juice\Compiler;

test('Variable rendering', function() {
    $compiler = new Compiler('{{ $foo }}', 'foo');
    expect($compiler->compile())->toBe('<?= htmlentities($foo) ?>');

    $compiler = new Compiler('{{$foo}}', 'foo');
    expect($compiler->compile())->toBe('<?= htmlentities($foo) ?>');

});

test('Variable rendering inside html', function() {
    $compiler = new Compiler('<h1>{{ $foo }}</h1>', 'foo');
    expect($compiler->compile())->toBe('<h1><?= htmlentities($foo) ?></h1>');
});

test('Multiple variable rendering inside html', function() {
    $compiler = new Compiler('<h1>{{ $foo }}</h1>
<h2>{{ $bar }}</h2>', 'foo');
expect($compiler->compile())->toBe('<h1><?= htmlentities($foo) ?></h1>
<h2><?= htmlentities($bar) ?></h2>');
});

test('Variable rendering from function', function() {
    $template = '{{ foo($bar, $baz) }}';
    $compiler = new Compiler($template, 'foo');
    expect($compiler->compile())->toBe('<?= htmlentities(foo($bar, $baz)) ?>');
});

test('Vulnerable variable rendering', function() {
    $compiler = new Compiler('{! $foo !}', 'foo');
    expect($compiler->compile())->toBe('<?= $foo ?>');

    $compiler = new Compiler('{!$foo!}', 'foo');
    expect($compiler->compile())->toBe('<?= $foo ?>');

});

test("Using php directives", function() {
    $compiler = new Compiler('<p>
<h1><?php echo ?></h1>
</p>', 'foo');
    expect(function() use($compiler) { $compiler->compile(); })->toThrow(ViewException::class, "Unexpected <?php at line 10");
});

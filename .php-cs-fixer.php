<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->notPath([
        __DIR__.'/tests/Support/ClosureSerializerTest.php'
    ]);
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PhpCsFixer' => true,
        'declare_strict_types' => true
    ])
        ->setFinder($finder)
        ->setRiskyAllowed(true)
;

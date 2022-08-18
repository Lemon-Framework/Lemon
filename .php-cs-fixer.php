<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PhpCsFixer' => true,
        'declare_strict_types' => true
    ])
        ->setFinder($finder)
        ->setRiskyAllowed(true)
;

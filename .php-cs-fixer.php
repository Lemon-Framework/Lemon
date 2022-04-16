<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PhpCsFixer' => true,
    ])
    ->setFinder($finder)
;

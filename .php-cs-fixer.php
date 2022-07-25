<?php

use Lemon\Fixers\FunctionExistsFixer;

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
//        ->registerCustomFixers([
//            new FunctionExistsFixer(),
//        ])
//        ->setRules([
//            'Lemon/function_exists' => false,
//        ])
;

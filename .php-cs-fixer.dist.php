<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony:risky' => true,
        '@PHP80Migration:risky' => true,
    ])
    ->setFinder($finder)
;

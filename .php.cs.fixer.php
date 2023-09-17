<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->exclude(['vendor', 'resources', 'node_modules', 'docker', 'boostrap', 'public', 'storage']);

$config = new PhpCsFixer\Config();

// https://cs.symfony.com/doc/rules/

//        '@PSR12'                            => true,
//        '@Symfony'                          => true,
//        '@PSR12:risky'                      => true,
//        '@Symfony:risky'                    => true,

return $config->setRules(
    [
        '@PSR12'                            => true,
        'lowercase_static_reference'        => false,      //https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/6673
        'visibility_required'               => false,
        'psr_autoloading'                   => false,
        'yoda_style'                        => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'native_function_invocation'        => false,
        'not_operator_with_successor_space' => true,
        'single_blank_line_at_eof'          => false,
        'no_unused_imports'                 => true,
        'binary_operator_spaces'            => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
            ],
        ],
    ]
)->setFinder($finder);

<?php

$date = date('Y');

$header = <<<EOF
Member Export bundle for Contao Open Source CMS.

@copyright  Copyright (c) $date, Codefog
@author     Codefog <https://codefog.pl>
@license    MIT
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            '@Symfony:risky' => true,
            'array_syntax' => ['syntax' => 'short'],
            'combine_consecutive_unsets' => true,
            'general_phpdoc_annotation_remove' => [
                'expectedException',
                'expectedExceptionMessage',
                'expectedExceptionMessageRegExp',
            ],
            'header_comment' => ['header' => $header],
            'heredoc_to_nowdoc' => true,
            'native_function_invocation' => true,
            'no_extra_consecutive_blank_lines' => [
                'break',
                'continue',
                'extra',
                'return',
                'throw',
                'use',
                'parenthesis_brace_block',
                'square_brace_block',
                'curly_brace_block',
            ],
            'no_unreachable_default_argument_value' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'ordered_class_elements' => true,
            'ordered_imports' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_order' => true,
            'psr4' => true,
            'strict_comparison' => true,
            'strict_param' => true,
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([__DIR__ . '/src'])
    );

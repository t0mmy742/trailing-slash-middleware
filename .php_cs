<?php

use PhpCsFixer\Fixer\Import\OrderedImportsFixer;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        'blank_line_before_statement' => false,
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => false,
        'no_extra_blank_lines' => ['tokens' => []],
        'ordered_imports' => [
            'importsOrder' => [
                OrderedImportsFixer::IMPORT_TYPE_CLASS,
                OrderedImportsFixer::IMPORT_TYPE_FUNCTION,
                OrderedImportsFixer::IMPORT_TYPE_CONST,
            ]
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_no_alias_tag' => false,
        'phpdoc_no_package' => false,
        'phpdoc_types_order' => false,
        'phpdoc_var_without_name' => false,
        'trailing_comma_in_multiline_array' => false,
        'yoda_style' => false,

        'phpdoc_annotation_without_dot' => false,
        'phpdoc_indent' => false,
        'phpdoc_inline_tag' => false,
        'phpdoc_no_access' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_no_useless_inheritdoc' => false,
        'phpdoc_return_self_reference' => false,
        'phpdoc_scalar' => false,
        'phpdoc_separation' => false,
        'phpdoc_single_line_var_spacing' => false,
        'phpdoc_summary' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_trim' => false,
        'phpdoc_types' => false,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    )->setUsingCache(false);

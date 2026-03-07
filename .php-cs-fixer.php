<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('storage')
    ->exclude('bootstrap/cache')
    ->exclude('node_modules')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        // PSR-12 Standard
        '@PSR12' => true,

        // Array syntax
        'array_syntax' => ['syntax' => 'short'],

        // Import ordering
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],

        // Remove unused imports
        'no_unused_imports' => true,

        // Trailing commas
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],

        // ===== DOCUMENTATION ENFORCEMENT ===== 👈 NEW RULES

        // Automatically add missing @param annotations
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => false, // Add @param even for typed parameters
        ],

        // Require return annotation for non-void methods
        'phpdoc_no_empty_return' => true,

        // Add @return void if method returns nothing
        'void_return' => true,

        // Ensure proper PHPDoc format
        'phpdoc_to_comment' => [
            'ignored_tags' => ['todo', 'var'],
        ],

        // ===== DOCBLOCK ALIGNMENT =====
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => ['param', 'property', 'return', 'throws', 'type', 'var', 'method'],
        ],
        'phpdoc_separation' => [
            'groups' => [
                ['deprecated', 'link', 'see', 'since'],
                ['author', 'copyright', 'license'],
                ['category', 'package', 'subpackage'],
                ['property', 'property-read', 'property-write'],
                ['param', 'return'],
            ],
        ],
        'phpdoc_summary' => true,
        'phpdoc_indent' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],

        // Operators
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'ternary_operator_spaces' => true,
        'not_operator_with_successor_space' => false,

        // Blank lines
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'blank_line_after_opening_tag' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,

        // Method and function spacing
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'single_trait_insert_per_statement' => true,

        // Class attributes
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
            ],
        ],

        // String concatenation
        'concat_space' => [
            'spacing' => 'one',
        ],

        // Casts
        'cast_spaces' => ['space' => 'single'],

        // Whitespace
        'no_whitespace_in_blank_line' => true,
        'whitespace_after_comma_in_array' => true,

        // Return type
        'return_type_declaration' => ['space_before' => 'none'],

        // Visibility
        'visibility_required' => [
            'elements' => ['method', 'property'],
        ],

        // Syntax
        'no_alternative_syntax' => true,
        'no_trailing_comma_in_singleline' => true,
        'single_quote' => true,
        'trim_array_spaces' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

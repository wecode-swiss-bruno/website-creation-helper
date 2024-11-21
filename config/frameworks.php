<?php

return [
    'css_frameworks' => [
        'bootstrap' => [
            'versions' => ['5.3', '4.6'],
            'package' => 'bootstrap',
            'cdn' => 'https://cdn.jsdelivr.net/npm/bootstrap@{version}/dist/css/bootstrap.min.css',
            'js_cdn' => 'https://cdn.jsdelivr.net/npm/bootstrap@{version}/dist/js/bootstrap.bundle.min.js',
            'dependencies' => ['@popperjs/core']
        ],
        'tailwind' => [
            'versions' => ['3.0', '2.0'],
            'package' => 'tailwindcss',
            'setup' => 'npx tailwindcss init',
            'config' => [
                'content' => [
                    './templates/**/*.{html,twig}',
                    './public/**/*.{html,js}'
                ],
                'theme' => [
                    'extend' => []
                ]
            ]
        ],
        'foundation' => [
            'versions' => ['6.7'],
            'package' => 'foundation-sites',
            'dependencies' => ['jquery', 'what-input']
        ],
        'bulma' => [
            'versions' => ['0.9'],
            'package' => 'bulma',
            'sass_import' => '@import "bulma/bulma";'
        ]
    ]
];

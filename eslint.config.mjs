import js from '@eslint/js';
import globals from 'globals';

export default [
    {
        files: ['**/*.js', '**/*.mjs'],
        languageOptions: {
            globals: {
                ...globals.browser,
                process: 'readonly',
            },
        },
    },
    js.configs.recommended,
    {
        ignores: [
            'node_modules/**',
            'vendor/**',
            'public/**',
            'storage/**',
            'bootstrap/cache/**',
            'dist/**',
            'coverage/**',
            '*.min.js',
        ],
    },
];

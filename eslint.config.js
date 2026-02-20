import js from '@eslint/js';
import prettier from 'eslint-config-prettier';
import globals from 'globals';
import typescript from 'typescript-eslint';

/** @type {import('eslint').Linter.Config[]} */
export default [
    js.configs.recommended,
    ...typescript.configs.recommended,
    {
        languageOptions: {
            globals: {
                ...globals.browser,
            },
        },
    },
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
    prettier, // Turn off all rules that might conflict with Prettier
];

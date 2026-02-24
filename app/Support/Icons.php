<?php

namespace App\Support;

class Icons
{
    /** @var array<string, array{source: string, inline: bool, path: string}> */
    private static array $icons = [];

    /** @var array<string, string> */
    private static array $cache = [];

    public static function register(string $name, string $source = 'public', bool $inline = false): void
    {
        $path = match ($source) {
            'public' => public_path("icons/{$name}.svg"),
            'resource' => resource_path("icons/{$name}.svg"),
            'blade' => $name, // blade uses view name, not file path
            default => public_path("icons/{$name}.svg"),
        };

        self::$icons[$name] = [
            'source' => $source,
            'inline' => $inline,
            'path' => $path,
        ];
    }

    public static function render(string $name, array $attrs = []): string
    {
        if (! isset(self::$icons[$name])) {
            return '';
        }

        $icon = self::$icons[$name];

        if ($icon['source'] === 'public' && ! $icon['inline']) {
            return self::renderImg($name, $attrs);
        }

        $content = self::readContents($name);

        if ($content === null) {
            return '';
        }

        return self::renderInline($content, $attrs);
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $names = array_keys(self::$icons);
        sort($names);

        return array_combine($names, $names);
    }

    /** @return array<string, array{source: string, inline: bool, path: string}> */
    public static function all(): array
    {
        return self::$icons;
    }

    private static function readContents(string $name): ?string
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        $icon = self::$icons[$name];

        if ($icon['source'] === 'blade') {
            if (! view()->exists("icons.{$name}")) {
                return null;
            }
            $content = view("icons.{$name}")->render();
        } else {
            if (! file_exists($icon['path'])) {
                return null;
            }
            $content = file_get_contents($icon['path']);
        }

        if ($content === false) {
            return null;
        }

        // Strip XML declaration if present
        $content = preg_replace('/<\?xml[^?]*\?>\s*/i', '', $content) ?? $content;

        self::$cache[$name] = trim($content);

        return self::$cache[$name];
    }

    private static function renderImg(string $name, array $attrs): string
    {
        $attrs = array_merge(['alt' => $name], $attrs);
        $attrs['src'] = "/icons/{$name}.svg";
        $attrStr = self::buildAttrString($attrs);

        return "<img{$attrStr}>";
    }

    private static function renderInline(string $content, array $attrs): string
    {
        if (empty($attrs)) {
            return $content;
        }

        // Merge attrs onto the opening <svg tag
        return (string) preg_replace_callback(
            '/<svg([^>]*)>/i',
            function (array $matches) use ($attrs): string {
                $existing = $matches[1];

                foreach ($attrs as $key => $value) {
                    $escaped = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

                    if ($key === 'class') {
                        // Append to existing class
                        if (preg_match('/\bclass="([^"]*)"/i', $existing)) {
                            $existing = preg_replace(
                                '/\bclass="([^"]*)"/i',
                                "class=\"$1 {$escaped}\"",
                                $existing
                            ) ?? $existing;
                        } else {
                            $existing .= " class=\"{$escaped}\"";
                        }
                    } else {
                        // Replace or append attr
                        $pattern = '/\b'.preg_quote($key, '/').'="[^"]*"/i';
                        if (preg_match($pattern, $existing)) {
                            $existing = preg_replace($pattern, "{$key}=\"{$escaped}\"", $existing) ?? $existing;
                        } else {
                            $existing .= " {$key}=\"{$escaped}\"";
                        }
                    }
                }

                return "<svg{$existing}>";
            },
            $content,
            1
        );
    }

    /** @param array<string, string> $attrs */
    private static function buildAttrString(array $attrs): string
    {
        $parts = [];
        foreach ($attrs as $key => $value) {
            $escaped = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            $parts[] = "{$key}=\"{$escaped}\"";
        }

        return $parts !== [] ? ' '.implode(' ', $parts) : '';
    }
}

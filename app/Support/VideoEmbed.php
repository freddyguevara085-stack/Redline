<?php

namespace App\Support;

use Illuminate\Support\Str;

class VideoEmbed
{
    public static function toEmbedUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);

        if ($url === '') {
            return null;
        }

        $parsed = parse_url($url);

        if (! $parsed || empty($parsed['host'])) {
            return null;
        }

        $host = strtolower($parsed['host']);
        $path = isset($parsed['path']) ? trim($parsed['path']) : '';

        if (Str::contains($host, 'youtu.be')) {
            $videoId = static::sanitizeYoutubeId(trim($path, '/'));

            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if (Str::contains($host, 'youtube.com')) {
            parse_str($parsed['query'] ?? '', $queryParams);
            $videoId = static::sanitizeYoutubeId($queryParams['v'] ?? null);

            if (! $videoId && Str::startsWith($path, '/embed/')) {
                $videoId = static::sanitizeYoutubeId(substr($path, 7));
            }

            if (! $videoId && Str::startsWith($path, '/shorts/')) {
                $videoId = static::sanitizeYoutubeId(substr($path, 8));
            }

            if (! $videoId && Str::startsWith($path, '/live/')) {
                $videoId = static::sanitizeYoutubeId(substr($path, 6));
            }

            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if (Str::contains($host, 'vimeo.com')) {
            $videoId = trim($path, '/');

            if (! $videoId || ! preg_match('/^[0-9]+$/', $videoId)) {
                return null;
            }

            return "https://player.vimeo.com/video/{$videoId}";
        }

        return null;
    }

    public static function supports(?string $url): bool
    {
        return (bool) static::toEmbedUrl($url);
    }

    protected static function sanitizeYoutubeId(?string $id): ?string
    {
        if (! $id) {
            return null;
        }

        $id = trim($id);

        return preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) ? $id : null;
    }
}

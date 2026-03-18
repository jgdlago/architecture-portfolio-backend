<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class PublicApiCache
{
    public static function remember(string $scope, array $parts, Closure $resolver): mixed
    {
        $ttl = max(1, (int) config('portfolio.public_cache_ttl', 600));

        return Cache::remember(
            self::key($scope, $parts),
            now()->addSeconds($ttl),
            $resolver,
        );
    }

    public static function bust(): void
    {
        Cache::forever(self::versionKey(), self::version() + 1);
    }

    private static function key(string $scope, array $parts): string
    {
        $version = self::version();
        $payloadHash = sha1(json_encode($parts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '');

        return "public-api:v{$version}:{$scope}:{$payloadHash}";
    }

    private static function version(): int
    {
        return (int) Cache::rememberForever(self::versionKey(), static fn (): int => 1);
    }

    private static function versionKey(): string
    {
        return 'public-api:version';
    }
}

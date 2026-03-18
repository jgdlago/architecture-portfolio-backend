<?php

return [
    'public_cache_ttl' => (int) env('PUBLIC_CONTENT_CACHE_TTL', 600),

    'image_optimization' => [
        'enabled' => filter_var(env('IMAGE_OPTIMIZATION_ENABLED', true), FILTER_VALIDATE_BOOL),
        'webp_quality' => (int) env('IMAGE_WEBP_QUALITY', 82),
        'thumbnail_widths' => array_values(array_filter(array_map(
            static fn ($width): int => (int) trim((string) $width),
            explode(',', (string) env('IMAGE_THUMB_SIZES', '300,600,1200')),
        ), static fn (int $value): bool => $value > 0)),
    ],
];

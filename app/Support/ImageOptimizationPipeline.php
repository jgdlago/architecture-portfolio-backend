<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class ImageOptimizationPipeline
{
    public static function run(string $diskName, string $path): array
    {
        if (! function_exists('imagecreatefromstring')) {
            return [];
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($diskName);

        if (! $disk->exists($path)) {
            return [];
        }

        $binary = $disk->get($path);
        $image = @imagecreatefromstring($binary);

        if ($image === false) {
            return [];
        }

        try {
            $quality = max(1, min(100, (int) config('portfolio.image_optimization.webp_quality', 82)));
            $widths = config('portfolio.image_optimization.thumbnail_widths', [300, 600, 1200]);

            $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
            $directory = trim((string) pathinfo($path, PATHINFO_DIRNAME), '.');
            $basename = (string) pathinfo($path, PATHINFO_FILENAME);
            $prefix = $directory !== '' ? $directory.'/' : '';

            self::compressOriginal($diskName, $path, $image, $extension, $quality);

            $webpPath = $prefix.$basename.'.webp';
            $disk->put($webpPath, self::toWebpBinary($image, $quality));

            $thumbs = [];
            $sourceWidth = imagesx($image);
            $sourceHeight = imagesy($image);

            foreach ($widths as $targetWidth) {
                $targetWidth = (int) $targetWidth;
                if ($targetWidth <= 0 || $targetWidth > $sourceWidth) {
                    continue;
                }

                $targetHeight = (int) round(($sourceHeight / max(1, $sourceWidth)) * $targetWidth);
                $thumb = self::resize($image, $targetWidth, $targetHeight);
                $thumbPath = $prefix.$basename.'_'.$targetWidth.'.webp';
                $disk->put($thumbPath, self::toWebpBinary($thumb, $quality));
                imagedestroy($thumb);

                $thumbs[] = [
                    'width' => $targetWidth,
                    'path' => $thumbPath,
                    'url' => $disk->url($thumbPath),
                ];
            }

            return [
                'webp' => [
                    'path' => $webpPath,
                    'url' => $disk->url($webpPath),
                ],
                'thumbnails' => $thumbs,
            ];
        } finally {
            imagedestroy($image);
        }
    }

    public static function derivativePaths(string $diskName, string $path): array
    {
        $disk = Storage::disk($diskName);

        $directory = trim((string) pathinfo($path, PATHINFO_DIRNAME), '.');
        $basename = (string) pathinfo($path, PATHINFO_FILENAME);
        $files = $disk->files($directory);

        $pattern = '/^'.preg_quote($basename, '/').'(?:_\\d+)?\\.webp$/';
        $matches = [];

        foreach ($files as $file) {
            $name = basename($file);
            if (preg_match($pattern, $name) === 1) {
                $matches[] = $file;
            }
        }

        return $matches;
    }

    private static function compressOriginal(string $diskName, string $path, \GdImage $image, string $extension, int $quality): void
    {
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return;
        }

        $binary = match ($extension) {
            'jpg', 'jpeg' => self::toJpegBinary($image, $quality),
            'png' => self::toPngBinary($image),
            default => self::toWebpBinary($image, $quality),
        };

        Storage::disk($diskName)->put($path, $binary);
    }

    private static function resize(\GdImage $source, int $targetWidth, int $targetHeight): \GdImage
    {
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            imagesx($source),
            imagesy($source),
        );

        return $canvas;
    }

    private static function toWebpBinary(\GdImage $image, int $quality): string
    {
        ob_start();
        imagewebp($image, null, $quality);
        return (string) ob_get_clean();
    }

    private static function toJpegBinary(\GdImage $image, int $quality): string
    {
        ob_start();
        imagejpeg($image, null, $quality);
        return (string) ob_get_clean();
    }

    private static function toPngBinary(\GdImage $image): string
    {
        ob_start();
        imagepng($image, null, 6);
        return (string) ob_get_clean();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Support\ImageOptimizationPipeline;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    private function diskName(): string
    {
        return config('filesystems.default') === 's3' ? 's3' : 'public';
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'folder' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\-_\/]+$/'],
        ]);

        $folder = $request->input('folder', 'uploads');
        $diskName = $this->diskName();
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($diskName);
        $path = $request->file('file')->store($folder, $diskName);
        $variants = [];

        if (config('portfolio.image_optimization.enabled', true)) {
            $variants = ImageOptimizationPipeline::run($diskName, $path);
        }

        return response()->json([
            'path' => $path,
            'url' => $disk->url($path),
            'variants' => $variants,
        ], 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string', 'max:500'],
        ]);

        $path = $request->input('path');
        $diskName = $this->diskName();
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($diskName);
        $derivatives = ImageOptimizationPipeline::derivativePaths($diskName, $path);
        $toDelete = array_values(array_unique(array_merge([$path], $derivatives)));

        $disk->delete($toDelete);

        return response()->json(['message' => 'Arquivo removido.']);
    }
}

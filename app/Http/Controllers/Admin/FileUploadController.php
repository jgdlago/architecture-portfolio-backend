<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'folder' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\-_\/]+$/'],
        ]);

        $folder = $request->input('folder', 'uploads');
        $path = $request->file('file')->store($folder, 'public');

        return response()->json([
            'path' => $path,
            'url' => asset('storage/' . $path),
        ], 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string', 'max:500'],
        ]);

        $path = $request->input('path');

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }

        return response()->json(['message' => 'Arquivo removido.']);
    }
}

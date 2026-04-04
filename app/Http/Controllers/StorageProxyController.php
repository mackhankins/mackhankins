<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageProxyController extends Controller
{
    public function __invoke(string $path): StreamedResponse
    {
        abort_unless(Storage::exists($path), 404);

        return response()->stream(function () use ($path) {
            echo Storage::get($path);
        }, 200, [
            'Content-Type' => Storage::mimeType($path),
            'Content-Length' => Storage::size($path),
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}

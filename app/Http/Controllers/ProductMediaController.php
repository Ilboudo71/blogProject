<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\ProductPhotoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductMediaController extends Controller
{
    public function __construct(private readonly ProductPhotoService $photos) {}

    public function show(Product $product): Response|StreamedResponse
    {
        $this->photos->rehydrateDiskFromDatabase($product);

        $path = is_string($product->photo) ? trim($product->photo) : '';

        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path, null, [
                'Cache-Control' => 'public, max-age=604800',
            ]);
        }

        $data = is_string($product->photo_data) ? $product->photo_data : '';

        if ($data === '') {
            abort(404);
        }

        $binary = base64_decode($data, true);

        if ($binary === false || $binary === '') {
            abort(404);
        }

        return response($binary, 200, [
            'Content-Type' => $product->photo_mime ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=604800',
            'Content-Length' => (string) strlen($binary),
        ]);
    }
}

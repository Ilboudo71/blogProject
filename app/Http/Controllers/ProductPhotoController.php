<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\ProductPhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductPhotoController extends Controller
{
    public function __construct(private readonly ProductPhotoService $photos) {}

    public function store(Request $request): JsonResponse
    {
        $this->validatePhoto($request);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('photo');

        $productId = $request->integer('product_id') ?: null;

        if ($productId) {
            $product = Product::query()->findOrFail($productId);
            $this->ensureCanManage($product);

            $path = $this->photos->replaceOnProduct($product, $file);
        } else {
            $path = $this->photos->store($file);
        }

        return response()->json([
            'path' => $path,
            'url' => Product::resolvePublicUrl($path),
            'message' => __('Photo mise à jour.'),
        ]);
    }

    private function validatePhoto(Request $request): void
    {
        $request->validate([
            'photo' => ['required', 'file', 'max:'.ProductPhotoService::MAX_KILOBYTES],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ], [
            'photo.required' => __('Veuillez sélectionner une image.'),
            'photo.max' => __('L’image ne doit pas dépasser 20 Mo.'),
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('photo');
        $mime = (string) $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        $isImageMime = str_starts_with($mime, 'image/');
        $isAllowedExtension = in_array($extension, ProductPhotoService::allowedExtensions(), true);

        if (! $isImageMime && ! $isAllowedExtension) {
            throw ValidationException::withMessages([
                'photo' => [__('Le fichier doit être une image (JPG, PNG, WEBP, GIF, BMP, HEIC…).')],
            ]);
        }
    }

    private function ensureCanManage(Product $product): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if (($user->role ?? null) === 'admin') {
            return;
        }

        if ((int) $product->user_id !== (int) $user->id) {
            abort(403);
        }
    }
}

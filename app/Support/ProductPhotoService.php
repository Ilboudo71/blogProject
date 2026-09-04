<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductPhotoService
{
    public const MAX_KILOBYTES = 20480;

    /**
     * @return list<string>
     */
    public static function allowedExtensions(): array
    {
        return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'heic', 'heif', 'avif'];
    }

    public function store(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        if (! in_array($extension, self::allowedExtensions(), true)) {
            $extension = 'jpg';
        }

        $filename = Str::lower((string) Str::ulid()).'.'.$extension;

        return $file->storeAs('products', $filename, 'public');
    }

    public function replace(?string $previousPath, UploadedFile $file): string
    {
        $path = $this->store($file);
        $this->deleteIfManaged($previousPath, $path);

        return $path;
    }

    public function replaceOnProduct(Product $product, UploadedFile $file): string
    {
        $path = $this->replace($product->photo, $file);

        $product->forceFill(['photo' => $path])->save();

        return $path;
    }

    public function deleteIfManaged(?string $path, ?string $except = null): void
    {
        $path = is_string($path) ? trim($path) : '';

        if ($path === '' || $path === $except) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (! str_starts_with($path, 'products/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

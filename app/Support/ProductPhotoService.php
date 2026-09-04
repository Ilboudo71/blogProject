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

    /**
     * @return array{path: string, mime: string, data: string}
     */
    public function storeWithPayload(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        if (! in_array($extension, self::allowedExtensions(), true)) {
            $extension = 'jpg';
        }

        $binary = $this->normalizeImageBinary($file, $extension);
        $mime = $binary['mime'];
        $contents = $binary['contents'];
        $extension = $binary['extension'];

        $filename = Str::lower((string) Str::ulid()).'.'.$extension;
        $path = 'products/'.$filename;

        Storage::disk('public')->put($path, $contents);

        return [
            'path' => $path,
            'mime' => $mime,
            'data' => base64_encode($contents),
        ];
    }

    public function store(UploadedFile $file): string
    {
        return $this->storeWithPayload($file)['path'];
    }

    public function replaceOnProduct(Product $product, UploadedFile $file): string
    {
        $payload = $this->storeWithPayload($file);
        $previous = $product->photo;

        $product->forceFill([
            'photo' => $payload['path'],
            'photo_mime' => $payload['mime'],
            'photo_data' => $payload['data'],
        ])->save();

        $this->deleteIfManaged($previous, $payload['path']);

        return $payload['path'];
    }

    public function persistDiskPhotoToDatabase(Product $product): void
    {
        $path = is_string($product->photo) ? trim($product->photo) : '';

        if ($path === '' || filled($product->photo_data)) {
            return;
        }

        if (! Storage::disk('public')->exists($path)) {
            return;
        }

        $contents = Storage::disk('public')->get($path);

        if ($contents === null || $contents === '') {
            return;
        }

        $product->forceFill([
            'photo_data' => base64_encode($contents),
            'photo_mime' => Storage::disk('public')->mimeType($path) ?: 'image/jpeg',
        ])->save();
    }

    public function rehydrateDiskFromDatabase(Product $product): bool
    {
        $path = is_string($product->photo) ? trim($product->photo) : '';
        $data = is_string($product->photo_data) ? $product->photo_data : '';

        if ($path === '' || $data === '') {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return true;
        }

        $binary = base64_decode($data, true);

        if ($binary === false || $binary === '') {
            return false;
        }

        Storage::disk('public')->put($path, $binary);

        return true;
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

    /**
     * @return array{contents: string, mime: string, extension: string}
     */
    private function normalizeImageBinary(UploadedFile $file, string $extension): array
    {
        $raw = file_get_contents($file->getRealPath());
        $mime = (string) ($file->getMimeType() ?: 'image/jpeg');

        if ($raw === false || $raw === '') {
            return [
                'contents' => '',
                'mime' => $mime,
                'extension' => $extension,
            ];
        }

        if (! function_exists('imagecreatefromstring')) {
            return [
                'contents' => $raw,
                'mime' => $mime,
                'extension' => $extension,
            ];
        }

        $image = @imagecreatefromstring($raw);

        if ($image === false) {
            return [
                'contents' => $raw,
                'mime' => $mime,
                'extension' => $extension,
            ];
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $max = 1600;

        if ($width > $max || $height > $max) {
            $ratio = min($max / max($width, 1), $max / max($height, 1));
            $newW = max(1, (int) round($width * $ratio));
            $newH = max(1, (int) round($height * $ratio));
            $resized = imagecreatetruecolor($newW, $newH);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();

        if (in_array($extension, ['png'], true)) {
            imagepng($image, null, 6);
            $mime = 'image/png';
        } elseif (in_array($extension, ['webp'], true) && function_exists('imagewebp')) {
            imagewebp($image, null, 82);
            $mime = 'image/webp';
        } else {
            imagejpeg($image, null, 82);
            $mime = 'image/jpeg';
            $extension = 'jpg';
        }

        $contents = (string) ob_get_clean();
        imagedestroy($image);

        return [
            'contents' => $contents !== '' ? $contents : $raw,
            'mime' => $mime,
            'extension' => $extension,
        ];
    }
}

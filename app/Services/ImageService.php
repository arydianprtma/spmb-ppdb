<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Memproses upload gambar dengan resize dan optimasi.
     */
    public static function processUpload(UploadedFile $file, string $directory, int $maxWidth = 1200, int $quality = 80): string
    {
        $filename = time() . '_' . uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        $img = Image::make($file->getRealPath());

        $img->resize($maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $content = (string) $img->encode('webp', $quality);
        Storage::disk('public')->put($path, $content);

        return $path;
    }
}

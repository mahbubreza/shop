<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageHelper
{
    public static function resizeToWebp(
        UploadedFile $file,
        string $path,
        int $width,
        int $height,
        int $quality = 80
    ): string {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $filename = Str::uuid() . '.webp';
        $fullPath = storage_path("app/public/{$path}/{$filename}");

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $image->toWebp($quality)->save($fullPath);

        return "{$path}/{$filename}";
    }
}

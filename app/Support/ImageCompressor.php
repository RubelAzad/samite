<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressor
{
    /**
     * Re-encode an uploaded image down to roughly $maxKb, storing it on the public disk.
     * Falls back to storing the file untouched for formats GD can't decode (e.g. .ico).
     */
    public static function store(UploadedFile $file, string $directory, int $maxKb = 100, bool $preserveTransparency = false): string
    {
        $info = @getimagesize($file->getRealPath());
        $image = $info ? self::decode($file->getRealPath(), $info[2]) : null;

        if (! $image) {
            return $file->store($directory, 'public');
        }

        if ($preserveTransparency) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        $image = self::capDimensions($image, 1200, $preserveTransparency);

        $extension = $preserveTransparency ? 'webp' : 'jpg';
        $maxBytes = $maxKb * 1024;
        $data = self::encodeUnderBudget($image, $maxBytes, $preserveTransparency);

        imagedestroy($image);

        $filename = $directory.'/'.Str::random(40).'.'.$extension;
        Storage::disk('public')->put($filename, $data);

        return $filename;
    }

    private static function decode(string $path, int $type)
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            IMAGETYPE_BMP => @imagecreatefrombmp($path),
            default => null,
        };
    }

    private static function capDimensions($image, int $maxDimension, bool $preserveTransparency)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxDimension && $height <= $maxDimension) {
            return $image;
        }

        $ratio = min($maxDimension / $width, $maxDimension / $height);

        return self::resize($image, (int) round($width * $ratio), (int) round($height * $ratio), $preserveTransparency);
    }

    private static function resize($image, int $width, int $height, bool $preserveTransparency)
    {
        $resized = imagecreatetruecolor($width, $height);

        if ($preserveTransparency) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        imagedestroy($image);

        return $resized;
    }

    private static function encode($image, int $quality, bool $preserveTransparency): string
    {
        ob_start();
        $preserveTransparency ? imagewebp($image, null, $quality) : imagejpeg($image, null, $quality);

        return ob_get_clean();
    }

    /** Steps quality down, then dimensions down, until under budget or visibly too small to shrink further. */
    private static function encodeUnderBudget($image, int $maxBytes, bool $preserveTransparency): string
    {
        $data = null;

        foreach ([85, 70, 55, 40, 25] as $quality) {
            $data = self::encode($image, $quality, $preserveTransparency);
            if (strlen($data) <= $maxBytes) {
                return $data;
            }
        }

        while (strlen($data) > $maxBytes && imagesx($image) > 100) {
            $image = self::resize($image, (int) (imagesx($image) * 0.8), (int) (imagesy($image) * 0.8), $preserveTransparency);
            $data = self::encode($image, 60, $preserveTransparency);
        }

        return $data;
    }
}

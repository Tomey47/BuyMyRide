<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress and store an image
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $path
     * @param int $quality
     * @return string
     */
    public function compressAndStore($image, $path, $quality = 80)
    {
        // Create image instance
        $img = $this->manager->read($image);

        // Resize image if it's too large
        if ($img->width() > 1920) {
            $img->scale(1920);
        }

        // Generate unique filename
        $filename = uniqid() . '.webp';

        // Store the image
        Storage::disk('public')->put($path . '/' . $filename, $img->toWebp($quality));

        return $path . '/' . $filename;
    }

    /**
     * Delete an image
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
} 
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ImageFilterService
{
    /**
     * Apply an image filter to the source image and save the filtered version.
     *
     * @param string $sourcePath Absolute path to the source image
     * @param string $filterType The filter type to apply
     * @return string Path relative to storage/app/public/ (e.g. menus/filtered/filename.jpg)
     */
    public function apply(string $sourcePath, string $filterType): string
    {
        if (!file_exists($sourcePath)) {
            throw new RuntimeException("Source image not found: {$sourcePath}");
        }

        // Ensure the filtered directory exists
        $filteredDir = storage_path('app/public/menus/filtered');
        if (!is_dir($filteredDir)) {
            mkdir($filteredDir, 0755, true);
        }

        // If no filter, just copy the original
        if (empty($filterType) || $filterType === 'none') {
            $originalName = pathinfo($sourcePath, PATHINFO_FILENAME);
            $timestamp = time();
            $newFilename = "{$originalName}_filtered_none_{$timestamp}.jpg";
            $destinationPath = $filteredDir . DIRECTORY_SEPARATOR . $newFilename;

            $image = $this->loadImage($sourcePath);
            imagejpeg($image, $destinationPath, 85);
            imagedestroy($image);

            return 'menus/filtered/' . $newFilename;
        }

        // Load the image
        $image = $this->loadImage($sourcePath);

        // Apply the filter
        $this->applyFilter($image, $filterType);

        // Build output filename
        $originalName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $timestamp = time();
        $newFilename = "{$originalName}_filtered_{$filterType}_{$timestamp}.jpg";
        $destinationPath = $filteredDir . DIRECTORY_SEPARATOR . $newFilename;

        // Save as JPEG with quality 85
        imagejpeg($image, $destinationPath, 85);
        imagedestroy($image);

        return 'menus/filtered/' . $newFilename;
    }

    /**
     * Load an image from file path, supporting JPEG, PNG, and WebP.
     *
     * @param string $path Absolute path to the image
     * @return \GdImage
     */
    private function loadImage(string $path)
    {
        $imageInfo = getimagesize($path);

        if ($imageInfo === false) {
            throw new RuntimeException("Cannot read image info: {$path}");
        }

        $mimeType = $imageInfo['mime'];

        $image = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => throw new RuntimeException("Unsupported image type: {$mimeType}"),
        };

        if ($image === false) {
            throw new RuntimeException("Failed to load image: {$path}");
        }

        return $image;
    }

    /**
     * Apply the specified filter to a GD image resource.
     *
     * @param \GdImage $image
     * @param string $filterType
     * @return void
     */
    private function applyFilter($image, string $filterType): void
    {
        match ($filterType) {
            'grayscale' => $this->applyGrayscale($image),
            'sepia' => $this->applySepia($image),
            'brightness' => $this->applyBrightness($image),
            'contrast' => $this->applyContrast($image),
            'sharpen' => $this->applySharpen($image),
            'blur' => $this->applyBlur($image),
            'vintage' => $this->applyVintage($image),
            default => throw new RuntimeException("Unknown filter type: {$filterType}"),
        };
    }

    private function applyGrayscale($image): void
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
    }

    private function applySepia($image): void
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_COLORIZE, 100, 50, 0);
    }

    private function applyBrightness($image): void
    {
        imagefilter($image, IMG_FILTER_BRIGHTNESS, 40);
    }

    private function applyContrast($image): void
    {
        imagefilter($image, IMG_FILTER_CONTRAST, -30);
    }

    private function applySharpen($image): void
    {
        $sharpenMatrix = [
            [0, -1, 0],
            [-1, 5, -1],
            [0, -1, 0],
        ];
        $divisor = array_sum(array_map('array_sum', $sharpenMatrix)); // = 1
        $offset = 0;
        imageconvolution($image, $sharpenMatrix, $divisor, $offset);
    }

    private function applyBlur($image): void
    {
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
    }

    private function applyVintage($image): void
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_COLORIZE, 120, 60, 0);
        imagefilter($image, IMG_FILTER_BRIGHTNESS, -20);
        imagefilter($image, IMG_FILTER_CONTRAST, -10);
    }

    /**
     * Get available filters with Indonesian display labels.
     *
     * @return array<string, string>
     */
    public static function getAvailableFilters(): array
    {
        return [
            'none' => 'Tanpa Filter',
            'grayscale' => 'Hitam Putih',
            'sepia' => 'Sepia (Klasik)',
            'brightness' => 'Kecerahan',
            'contrast' => 'Kontras',
            'sharpen' => 'Pertajam',
            'blur' => 'Buram',
            'vintage' => 'Vintage (Jadul)',
        ];
    }
}

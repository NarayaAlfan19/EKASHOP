<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Memproses, mengonversi ke WebP, dan menyimpan gambar menggunakan GD bawaan PHP
     */
    public function uploadAndProcess(UploadedFile $file, string $directory = 'flowers'): string
    {
        $tmpPath = $file->getRealPath();
        list($width, $height, $type) = getimagesize($tmpPath);

        // Membuat resource gambar berdasarkan tipe file asli
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($tmpPath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($tmpPath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($tmpPath);
                break;
            default:
                throw new \Exception('Format gambar tidak didukung.');
        }

        // Target ukuran (rasio 4:5, contoh 800x1000)
        $targetWidth = 800;
        $targetHeight = 1000;

        $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Mempertahankan transparansi untuk PNG/WebP
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        // Resample gambar agar pas ukurannya
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $filename = uniqid('flower_') . '_' . time() . '.webp';
        $path = $directory . '/' . $filename;

        // Menyimpan sementara di storage publik sebagai WebP
        $destinationPath = storage_path('app/public/' . $path);
        
        // Pastikan folder tujuan ada
        if (!file_exists(dirname($destinationPath))) {
            mkdir(dirname($destinationPath), 0755, true);
        }

        imagewebp($newImage, $destinationPath, 80);

        // Bersihkan memori
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $path;
    }

    public function deleteImage(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

/**
 * Servicio para gestionar la subida y eliminación de imágenes en Cloudinary.
 * 
 * Este servicio encapsula toda la lógica de interacción con la API de Cloudinary,
 * permitiendo subir, actualizar y eliminar imágenes de productos.
 */
class CloudinaryService
{
    /**
     * Carpeta donde se almacenan las imágenes de productos en Cloudinary.
     */
    private const PRODUCTS_FOLDER = 'tecnooutlet/products';

    /**
     * Sube una imagen a Cloudinary.
     *
     * @param UploadedFile $image Archivo de imagen a subir
     * @return array Contiene 'url' y 'public_id' de la imagen subida
     */
    public function uploadImage(UploadedFile $image): array
    {
        $result = Cloudinary::upload($image->getRealPath(), [
            'folder' => self::PRODUCTS_FOLDER,
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]
        ]);

        return [
            'url' => $result->getSecurePath(),
            'public_id' => $result->getPublicId(),
        ];
    }

    /**
     * Elimina una imagen de Cloudinary.
     *
     * @param string|null $publicId ID público de la imagen en Cloudinary
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function deleteImage(?string $publicId): bool
    {
        if (empty($publicId)) {
            return false;
        }

        try {
            Cloudinary::destroy($publicId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error al eliminar imagen de Cloudinary: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza una imagen: elimina la anterior y sube la nueva.
     *
     * @param UploadedFile $newImage Nueva imagen a subir
     * @param string|null $oldPublicId ID público de la imagen anterior (opcional)
     * @return array Contiene 'url' y 'public_id' de la nueva imagen
     */
    public function updateImage(UploadedFile $newImage, ?string $oldPublicId = null): array
    {
        // Eliminar imagen anterior si existe
        if ($oldPublicId) {
            $this->deleteImage($oldPublicId);
        }

        // Subir nueva imagen
        return $this->uploadImage($newImage);
    }
}

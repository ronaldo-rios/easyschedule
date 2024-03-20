<?php

namespace App\adms\Models\helpers;

class UploadImage
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * Upload user image
     * @param array $dataImage
     * @return string|bool
     */
    public static function uploadUserImage(array $dataImage): string|bool
    {
        // Create directory if not exists with user id
        $path = $_SERVER['DOCUMENT_ROOT'] . "/" . PATH_USER_IMAGE . $dataImage['id'] . "/";
        if (! file_exists($path) && ! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Validate image extension
        $fileExtension = strtolower(pathinfo($dataImage['image']['name'], PATHINFO_EXTENSION));
        if (! in_array($fileExtension, self::ALLOWED_EXTENSIONS)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Formato de imagem inválido!</div>";
            return false;
        }

        $newFileName = uniqid() . "." . $fileExtension;

        if (move_uploaded_file($dataImage['image']['tmp_name'], $path . $newFileName)) {
            $dataImage['image'] = (string) $newFileName;
            return $dataImage['image'];
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao fazer upload da imagem!</div>";
            return false;
        }
    }

    /**
     * Delete old image from user directory server
     * @param array $image
     * @param array $resultDbImage
     * @return void
     */
    public static function deleteBeforeImage(array $data, string $resultDbImage): void
    {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . PATH_USER_IMAGE . $data['id'] . "/" . $resultDbImage;
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }
}
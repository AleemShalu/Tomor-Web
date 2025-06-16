<?php

namespace App\Helpers;

use Exception;

class FormHelper
{
    public static function resize($newWidth, $targetFile, $originalFile)
    {
        $info = getimagesize($originalFile);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                throw new Exception('Unknown image type.');
        }

        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);

        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);

        if ($mime == 'image/png' || $mime == 'image/gif') {
            imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }

        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if ($image_save_func($tmp, $targetFile)) {
            return true;
        }

        return false;
    }

    public static function resizeFromBinary($newWidth, $targetFile, $binaryImageData)
    {
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'image_');
        file_put_contents($tmpFilePath, $binaryImageData);

        return self::resize($newWidth, $targetFile, $tmpFilePath);
    }
}

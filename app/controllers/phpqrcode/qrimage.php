<?php
// phpqrcode/qrimage.php

/**
 * PHP QR Code image output via GD2
 *
 * @package PHPQRCode
 * @license LGPL 3
 */

define('QR_IMAGE', true);

class QRimage
{
    /**
     * Render QR Code as PNG.
     *
     * @param array       $frame          QR matrix (0/1)
     * @param string|bool $filename       Output filename or false for direct output
     * @param int         $pixelPerPoint  Pixel size of each module
     * @param int         $outerFrame     Margin around QR code in modules
     * @param bool        $saveandprint   Save to file and output when true
     */
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint = false)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            header('Content-Type: image/png');
            imagepng($image);
        } else {
            imagepng($image, $filename);
            if ($saveandprint) {
                header('Content-Type: image/png');
                imagepng($image);
            }
        }

        imagedestroy($image);
    }

    /**
     * Render QR Code as JPEG.
     *
     * @param array       $frame          QR matrix (0/1)
     * @param string|bool $filename       Output filename or false for direct output
     * @param int         $pixelPerPoint  Pixel size of each module
     * @param int         $outerFrame     Margin around QR code in modules
     * @param int         $quality        JPEG quality (0-100)
     */
    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $quality = 85)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            header('Content-Type: image/jpeg');
            imagejpeg($image, null, $quality);
        } else {
            imagejpeg($image, $filename, $quality);
        }

        imagedestroy($image);
    }

    /**
     * Create a GD image from QR matrix.
     *
     * @param array $frame          QR matrix
     * @param int   $pixelPerPoint  Pixel size of each module
     * @param int   $outerFrame     Margin around code in modules
     * @return resource             GD image resource
     * @throws \Exception         If GD is not available
     */
    private static function image(array $frame, int $pixelPerPoint = 4, int $outerFrame = 4)
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new \Exception('GD library is not available. Enable php-gd extension.');
        }

        $h = count($frame);
        $w = strlen($frame[0]);

        // Dimensions in modules including margin
        $modulesX = $w + 2 * $outerFrame;
        $modulesY = $h + 2 * $outerFrame;

        // Create base image at module resolution
        $baseImage = imagecreatetruecolor($modulesX, $modulesY);
        $white = imagecolorallocate($baseImage, 255, 255, 255);
        $black = imagecolorallocate($baseImage,   0,   0,   0);
        imagefilledrectangle($baseImage, 0, 0, $modulesX - 1, $modulesY - 1, $white);

        // Draw pixels
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                if ($frame[$y][$x] === '1') {
                    imagesetpixel($baseImage, $x + $outerFrame, $y + $outerFrame, $black);
                }
            }
        }

        // Scale to desired pixel size
        $imgW = $modulesX * $pixelPerPoint;
        $imgH = $modulesY * $pixelPerPoint;
        $targetImage = imagecreatetruecolor($imgW, $imgH);
        imagefilledrectangle($targetImage, 0, 0, $imgW - 1, $imgH - 1, $white);
        imagecopyresized(
            $targetImage,
            $baseImage,
            0,
            0,
            0,
            0,
            $imgW,
            $imgH,
            $modulesX,
            $modulesY
        );
        imagedestroy($baseImage);

        return $targetImage;
    }
}

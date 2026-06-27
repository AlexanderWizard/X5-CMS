<?php

namespace App\Modules\Gallery\Support;

/**
 * Обработка изображений галереи на GD (порт Image-компонента оригинального
 * модуля Gallery). Делает уменьшённую копию (вписывание в бокс) и квадратные
 * обрезанные превью.
 *
 * Расширение GD проверяется на лету — при его отсутствии методы тихо
 * возвращают false (загрузка тогда сохранит только исходник без превью).
 */
class ImageProcessor
{
    /**
     * Уменьшить изображение, вписав его в бокс {$maxW}×{$maxH} (без обрезки,
     * с сохранением пропорций), и сохранить как JPEG.
     *
     * @return array{0:int,1:int}|false [ширина, высота] результата либо false
     */
    public static function fitJpeg(string $source, string $dest, int $maxW = 1680, int $maxH = 1680, int $quality = 90): array|false
    {
        $img = self::open($source);
        if (!$img) {
            return false;
        }

        [$w, $h]   = [imagesx($img), imagesy($img)];
        [$nw, $nh] = self::fitBox($w, $h, $maxW, $maxH);

        $target = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($target, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

        self::ensureDir($dest);
        imagejpeg($target, $dest, $quality);

        imagedestroy($target);
        imagedestroy($img);

        return [$nw, $nh];
    }

    /**
     * Квадратное обрезанное по центру превью размером {$size}×{$size}.
     */
    public static function squareThumbnail(string $source, string $dest, int $size = 600, int $quality = 88): bool
    {
        $img = self::open($source);
        if (!$img) {
            return false;
        }

        $w    = imagesx($img);
        $h    = imagesy($img);
        $crop = min($w, $h);
        $x    = (int) (($w - $crop) / 2);
        $y    = (int) (($h - $crop) / 2);

        $final = imagecreatetruecolor($size, $size);
        imagecopyresampled($final, $img, 0, 0, $x, $y, $size, $size, $crop, $crop);

        self::ensureDir($dest);
        $ok = imagejpeg($final, $dest, $quality);

        imagedestroy($final);
        imagedestroy($img);

        return (bool) $ok;
    }

    /**
     * Размеры исходника (без обработки) — [width, height] либо [0, 0].
     *
     * @return array{0:int,1:int}
     */
    public static function dimensions(string $source): array
    {
        $info = @getimagesize($source);

        return $info ? [(int) $info[0], (int) $info[1]] : [0, 0];
    }

    /** Открыть исходник как GD-ресурс по его MIME-типу. */
    private static function open(string $source): \GdImage|false
    {
        if (!extension_loaded('gd') || !is_file($source)) {
            return false;
        }

        $info = @getimagesize($source);
        if (!$info) {
            return false;
        }

        return match ($info['mime'] ?? '') {
            'image/jpeg' => @imagecreatefromjpeg($source),
            'image/png'  => @imagecreatefrompng($source),
            'image/gif'  => @imagecreatefromgif($source),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            default      => false,
        };
    }

    /**
     * Вписать {$w}×{$h} в бокс {$maxW}×{$maxH} (не увеличивая).
     *
     * @return array{0:int,1:int}
     */
    private static function fitBox(int $w, int $h, int $maxW, int $maxH): array
    {
        if ($w <= $maxW && $h <= $maxH) {
            return [$w, $h];
        }

        $ratio = min($maxW / $w, $maxH / $h);

        return [max(1, (int) round($w * $ratio)), max(1, (int) round($h * $ratio))];
    }

    private static function ensureDir(string $file): void
    {
        $dir = dirname($file);

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
    }
}

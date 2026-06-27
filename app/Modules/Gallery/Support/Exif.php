<?php

namespace App\Modules\Gallery\Support;

/**
 * Чтение EXIF-метаданных JPEG (порт Exif-компонента оригинального модуля).
 *
 * Возвращает нормализованный набор полей фото: камера, объектив, выдержка,
 * диафрагма, ISO, дата съёмки. Устойчив к отсутствию расширения exif и к
 * битым данным — недостающие поля просто пустые.
 */
class Exif
{
    /**
     * @return array{
     *   camera:?string, lens:?string, shutter_speed:?string,
     *   focal_length:?string, iso:?int, taken_at:?string, year:?int
     * }
     */
    public static function read(string $sourceFile): array
    {
        $data = [
            'camera'        => null,
            'lens'          => null,
            'shutter_speed' => null,
            'focal_length'  => null,
            'iso'           => null,
            'taken_at'      => null,
            'year'          => null,
        ];

        if (!extension_loaded('exif') || !is_file($sourceFile)) {
            return $data;
        }

        $exif = @exif_read_data($sourceFile);
        if (!$exif) {
            return $data;
        }

        // Make + Model → «камера»; отдельно тег объектива, если есть.
        $make  = self::clean($exif['Make'] ?? '');
        $model = self::clean($exif['Model'] ?? '');
        $data['camera'] = trim($make && stripos($model, $make) === false ? "{$make} {$model}" : $model) ?: null;

        $data['lens'] = self::clean(
            $exif['UndefinedTag:0xA434'] ?? $exif['LensModel'] ?? $exif['UndefinedTag:0x9A00'] ?? ''
        ) ?: null;

        $data['shutter_speed'] = self::formatShutter($exif['ExposureTime'] ?? '');
        $data['focal_length']  = self::formatAperture($exif['FNumber'] ?? ($exif['ApertureValue'] ?? ''));

        if (isset($exif['ISOSpeedRatings'])) {
            $iso = is_array($exif['ISOSpeedRatings']) ? reset($exif['ISOSpeedRatings']) : $exif['ISOSpeedRatings'];
            $data['iso'] = (int) $iso ?: null;
        }

        $taken = $exif['DateTimeOriginal'] ?? $exif['DateTime'] ?? '';
        if ($taken) {
            $data['taken_at'] = self::clean($taken);
            $data['year']     = (int) substr($taken, 0, 4) ?: null;
        }

        return $data;
    }

    /** «1/250 c» из рациональной выдержки EXIF (или уже готовой строки). */
    public static function formatShutter(string|int|float $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (preg_match('~^\d+/\d+$~', $value)) {
            [$n, $d] = array_map('floatval', explode('/', $value));
            if ($d > 0 && $n > 0) {
                $sec = $n / $d;

                return $sec >= 1
                    ? rtrim(rtrim(number_format($sec, 1), '0'), '.') . ' c'
                    : '1/' . (int) round(1 / $sec) . ' c';
            }
        }

        return self::clean($value);
    }

    /** «f/1.8» из рационального FNumber EXIF (или уже готовой строки). */
    public static function formatAperture(string|int|float $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (preg_match('~^\d+/\d+$~', $value)) {
            [$n, $d] = array_map('floatval', explode('/', $value));
            if ($d > 0) {
                return 'f/' . rtrim(rtrim(number_format($n / $d, 1), '0'), '.');
            }
        }

        return self::clean($value);
    }

    private static function clean(string|int|float $str): string
    {
        $str = (string) $str;
        $str = str_replace(['|', "'", '"', '{', '}'], '', $str);

        return trim(mb_substr($str, 0, 64));
    }
}

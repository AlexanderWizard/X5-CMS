<?php

namespace App\Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Правило файрвола — разрешённый IP-адрес или подсеть (CIDR) для доступа в админку.
 *
 * @property int    $id
 * @property string $ip_address
 * @property string $description
 * @property bool   $is_active
 * @property string $created_at
 */
class FirewallRule extends Model
{
    protected $table = 'firewall_rules';

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Разрешён ли доступ с указанного IP.
     *
     * Если активных правил нет — доступ открыт (защита от блокировки).
     * Иначе IP должен совпасть хотя бы с одним активным правилом.
     */
    public static function isAllowed(string $ip): bool
    {
        $rules = static::query()
            ->where('is_active', 1)
            ->pluck('ip_address');

        if ($rules->isEmpty()) {
            return true;
        }

        foreach ($rules as $rule) {
            if (static::ipMatches($ip, $rule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Совпадает ли IP с правилом (точный адрес или CIDR-подсеть).
     */
    public static function ipMatches(string $ip, string $rule): bool
    {
        $rule = trim($rule);

        if ($rule === '') {
            return false;
        }

        if (str_contains($rule, '/')) {
            return static::cidrMatch($ip, $rule);
        }

        $ipBin   = @inet_pton($ip);
        $ruleBin = @inet_pton($rule);

        return $ipBin !== false
            && $ruleBin !== false
            && $ipBin === $ruleBin;
    }

    /**
     * Проверка IP на принадлежность CIDR-подсети (IPv4 и IPv6).
     */
    private static function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $bits] = explode('/', $cidr, 2);

        if (!ctype_digit($bits)) {
            return false;
        }

        $bits      = (int) $bits;
        $ipBin     = @inet_pton($ip);
        $subnetBin = @inet_pton($subnet);

        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        // IPv4 против IPv6 — разная длина
        if (strlen($ipBin) !== strlen($subnetBin)) {
            return false;
        }

        $bytes     = intdiv($bits, 8);
        $remainder = $bits % 8;

        if ($bytes > 0 && substr($ipBin, 0, $bytes) !== substr($subnetBin, 0, $bytes)) {
            return false;
        }

        if ($remainder > 0) {
            $mask = chr((0xff << (8 - $remainder)) & 0xff);

            if ((ord($ipBin[$bytes]) & ord($mask)) !== (ord($subnetBin[$bytes]) & ord($mask))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Валиден ли ввод — корректный IP-адрес или CIDR-подсеть.
     */
    public static function isValidIpOrCidr(string $value): bool
    {
        $value = trim($value);

        if (str_contains($value, '/')) {
            [$ip, $bits] = explode('/', $value, 2);

            if (!ctype_digit($bits)) {
                return false;
            }

            $bin = @inet_pton($ip);

            if ($bin === false) {
                return false;
            }

            $max = strlen($bin) === 4 ? 32 : 128;

            return (int) $bits >= 0 && (int) $bits <= $max;
        }

        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}

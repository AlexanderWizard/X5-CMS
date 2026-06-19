<?php

namespace App\Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string      $message
 * @property string|null $ip_address
 * @property int    $is_processed
 * @property string $created_at
 */
class Feedback extends Model
{
    protected $table = 'cms_feedback';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'message',
        'ip_address',
    ];
}

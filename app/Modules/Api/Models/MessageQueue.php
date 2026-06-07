<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $channel
 * @property string $body
 * @property int    $is_processed
 * @property string $created_at
 */
class MessageQueue extends Model
{
    protected $table = 'messages_queue';

    public $timestamps = false;

    protected $fillable = [
        'channel',
        'body',
    ];
}

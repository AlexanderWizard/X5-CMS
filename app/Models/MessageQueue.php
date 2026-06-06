<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $channel
 * @property string $body
 * @property string $created_at
 *
 * @method static static create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder where(string $column, mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder whereIn(string $column, array $values)
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

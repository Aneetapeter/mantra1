<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelVisit extends Model
{
    protected $fillable = [
        'user_id',
        'channel_name',
        'channel_url',
        'channel_image'
    ];
}

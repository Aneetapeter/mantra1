<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['user_id', 'name', 'color'];

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

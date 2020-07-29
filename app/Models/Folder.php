<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function files() {
        return $this->hasMany('App\Models\File');
    }

    public function folder() {
        return $this->belongsTo(self::class);
    }

    public function folders() {
        return $this->hasMany(self::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function folder() {
        return $this->belongsTo('App\Models\Folder');
    }
}

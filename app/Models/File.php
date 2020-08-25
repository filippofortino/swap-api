<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes, GeneratesUuid;
    
    public function folder() {
        return $this->belongsTo('App\Models\Folder');
    }
}

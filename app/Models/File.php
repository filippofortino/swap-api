<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes, GeneratesUuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at'];
    
    public function folder() {
        return $this->belongsTo('App\Models\Folder');
    }
}

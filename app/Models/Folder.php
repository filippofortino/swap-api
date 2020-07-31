<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;

    public function parentFolder() {
        return $this->folder()->with('parentFolder');
    }

    public function breadcrumbs() {
        $folder = $this->loadMissing('parentFolder');

        $breadcrumbs = collect([]);
        while($folder->parentFolder !== null) {
            $breadcrumbs->prepend($folder->only(['id', 'uuid', 'name']));
            $folder = $folder->parentFolder;
        }
        $breadcrumbs->prepend($folder->only(['id', 'uuid', 'name']));

        return $breadcrumbs;
    }
    
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

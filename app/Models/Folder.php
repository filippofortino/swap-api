<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes, GeneratesUuid;

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

    /**
     * Delete the current folder and all of it's content.
     * 
     * @param bool $force
     * @return null
     */
    public function deleteWithContent($force = false) {
        $force ? $this->files()->forceDelete() : $this->files()->delete();
        $this->folders()->get()->each(fn($folder) => $folder->deleteWithContent($force));
        $force ? $this->forceDelete() : $this->delete();
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

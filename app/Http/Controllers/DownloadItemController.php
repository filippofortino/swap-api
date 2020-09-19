<?php

namespace App\Http\Controllers;

use Zip;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DownloadItemController extends Controller
{
    protected $zipContent = [];

    public function download(Request $request)
    {
        $validated = $request->validate([
            'files' => 'array',
            'files.*' => 'numeric|exists:files,id',
            'folders' => 'array',
            'folders.*' => 'numeric|exists:folders,id',
        ]);
        
        $files = File::find($validated['files']);
        $folders = Folder::find($validated['folders']);
        
        // If only one file is being requested just download that file instead of zipping
        if($files->count() === 1 && $folders->count() === 0) {
            $file = $files->first();
            return response()->download(storage_path("app/{$file->path}"),'', ['test' => 'idk']);
        }
        
        foreach($folders as $folder) {
            $this->addNestedFilesToZip($folder);
        }

        foreach($files as $file) {
            $this->addToZip($file);
        }

        $filename = Str::random(10);
        return Zip::create("{$filename}.zip", $this->zipContent);
    }

    protected function addNestedFilesToZip($folder) {
        $files = $folder->files;
        $folders = $folder->folders;

        foreach($files as $file) {
            $this->addToZip($file, true);
        }

        foreach($folders as $folder) {
            $this->addNestedFilesToZip($folder);
        }
    }

    protected function addToZip($file, $nested = false) {
        $this->zipContent[storage_path("app/{$file->path}")] = $nested ? $file->computedPath() : $file->name;
    }
}

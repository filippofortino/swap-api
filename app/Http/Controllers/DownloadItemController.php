<?php

namespace App\Http\Controllers;

use Zip;
use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Folder;

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
        
        foreach($folders as $folder) {
            $this->getAllFiles($folder);
        }

        foreach($files as $file) {
            $this->zipContent[storage_path("app/{$file->path}")] = $file->name;
        }

        return Zip::create('test.zip', $this->zipContent);
    }

    protected function getAllFiles($folder) {
        $files = $folder->files;
        $folders = $folder->folders;

        foreach($files as $file) {
            $this->zipContent[storage_path("app/{$file->path}")] = $file->computedPath();
        }

        foreach($folders as $folder) {
            $this->getAllFiles($folder);
        }
    }
}

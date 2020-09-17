<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadItemController extends Controller
{
    public function show(Request $request)
    {
        $validated = $request->validate([
            'files' => 'array',
            'files.*' => 'numeric|exists:files,id',
            'folders' => 'array',
            'folders.*' => 'numeric|exists:folders,id',
        ]);

        dd($validated);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreUploadedFileRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUploadedFileRequest $request)
    {
        
        $validated = $request->validated();

        $uploadedFile = $request->file('filepond');

        // Save the file only if it doesn't already exists on
        // the server.
        $fileAlreadyExists = File::withTrashed()->where('hash', $request->file_hash)->first();
        if(!$fileAlreadyExists) {
            $savedFile = $uploadedFile->store('files', 'local');

            $file = File::create([
                'folder_id' => $request->parent_folder,
                'name' => $request->file_name,
                'path' => $savedFile,
                'mime_type' => Storage::mimeType($savedFile),
                'size' => Storage::size($savedFile),
                'hash' => $request->file_hash
            ]);
        } else {
            $file = $fileAlreadyExists->replicate();

            $file->folder_id = $request->parent_folder;
            $file->uuid = Str::uuid();
            $file->name = $request->file_name;
            $file->save();
        }

        return response()->json($file);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }

    public function revert(Request $request) {
        $file_uuid = $request->getContent();

        $file = File::whereUuid($file_uuid)->forceDelete();
    }
}

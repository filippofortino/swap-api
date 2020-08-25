<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

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
    public function store(Request $request)
    {
        if($request->file('filepond')->isValid() && $request->has('filepond')) {
            // $uploadedFile = $request->file('filepond')->store("tmp", 'local');
            $uploadedFile = $request->file('filepond');
            $file_hash = hash_file('sha256', $uploadedFile->path());
            $parent_folder = json_decode($request->input('filepond'))->parent_folder;

            // Save the file only if it doesn't already exists on
            // the server.
            $fileAlreadyExists = File::withTrashed()->where('hash', $file_hash)->first();
            if(!$fileAlreadyExists) {
                $savedFile = $uploadedFile->store('files', 'local');

                $file = new File();
                $file->folder_id = $parent_folder;
                $file->name = $uploadedFile->getClientOriginalName();
                $file->path = $savedFile;
                $file->mime_type = Storage::mimeType($savedFile);
                $file->size = Storage::size($savedFile);
                $file->hash = $file_hash;
                $file->save();
            } else {
                $file = $fileAlreadyExists->replicate();
                $file->folder_id = $parent_folder;
                $file->uuid = Str::uuid();
                $file->name = $uploadedFile->getClientOriginalName();
                $file->save();
            }

            return response()->json($file);
        }
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

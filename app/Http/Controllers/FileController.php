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
        $file->update(
            $request->validate([
                'folder_id' => 'required|integer|exists:folders',
                'name' => 'required|string|min:1'
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     * 
     * We do not take the $file as for the route definition
     * in this method.
     * To keep the routes file clean with just the
     * API Resource definition, we will call this endpoint
     * with a dummy parameter like 'files/delete' but 
     * we're not actually gonna use it, it's just to make
     * it work.
     * Insteat we will take the $request as all the necessary 
     * data will be passed there.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'numeric|exists:files,id'
        ]);

        foreach($request->items as $file) {
            File::find($file)->delete();
        }

        return response()->json($request->items);
    }

    /**
     * Remove a newly uploaded file
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function revert(Request $request) {
        $file_uuid = $request->getContent();

        $file = File::whereUuid($file_uuid)->forceDelete();
    }
}

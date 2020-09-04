<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return response()->json(
            Folder::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'parent_folder' => 'required|integer|exists:folders,id',
            'name' => 'required|string|max:80|min:1'
        ]);

        $folder = new Folder();
        $folder->folder_id = $request->parent_folder;
        $folder->name = $request->name;

        $folder->save();

        return response()->json($folder);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Folder::with(['files', 'folders' => function($query) {
            $query->where('id', '!=', 1);
        }]);

        // Allow to make a call like: folders/root instead of folders/uuid
        // for the root folder
        if($id === 'root') {
            $folder = $folder->find(1);
        } else {
            $folder = $folder->firstWhere('uuid', $id);
        }

        $folder ?: abort(Response::HTTP_NOT_FOUND);

        return response()->json([
            'folder' => $folder,
            // Breadcrumbs are not needed when in root, so we avoid a useless query
            'breadcrumbs' => $id !== 'root' ? $folder->breadcrumbs() : null
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'numeric|exists:folders,id'
        ]);

        foreach($request->items as $folder) {
            Folder::find($folder)->deleteWithContent();
        }

        return response()->json($request->items);
    }
}

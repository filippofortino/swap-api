<?php

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('folders', 'FolderController');
Route::delete('files/revert', 'FileController@revert')->name("files.revert");
Route::apiResource('files', 'FileController');
Route::post('items/download', 'DownloadItemController@show')->name('items.download');

Route::get('download-test', function() {
    // dd(Folder::find(19)->files);
    $files = Folder::find(94)->files;

    // dd($files);
    // $files->each(function ($model) { $model->setAppends(['computedPath']); });
    // $zipcontent = $files->pluck('computed_path', 'path');
    // $zipcontent = $files->map(function($file) {
    //     return [storage_path('app/files/' . $file->path) => $file->computedPath() . '/' . $file->name];
    // });
    
    // dd($zipcontent);
    $zipcontent = [];

    // $files->each(function($file) use (&$zipcontent) {
    //     $zipcontent[storage_path('app/' . $file->path)] = $file->computedPath() . '/' . $file->name;
    // });
    foreach($files as $file) {
        $zipcontent[storage_path('app/' . $file->path)] = $file->computedPath() . '/' . $file->name;
    }

    // foreach($zipcontent as $path => $computedPath) {
    //     $zipcontent[storage_path('app/' . $path)] = $zipcontent[$path];
    //     unset($zipcontent[$path]);
    // }

    dd($zipcontent);

    return Zip::create('test.zip', $zipcontent->toArray());
});

// Route::get('download-test', function() {
//     $zipFile = storage_path('app/zip/download.zip');

//     $archive = new \ZipArchive();
//     if($archive->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
//         $files = Storage::files('files');

//         foreach($files as $file) {
//             $archive->addFile(storage_path('app/' . $file,), basename($file));
//         }
    
//         $archive->close();
//     }

    

//     // ddd(Storage::files('files'));
//     // ddd(basename('files/8Iz3G4yvpD9D58vjR3Haph8lBDj9ZgyJN9quy8So.torrent'));
//     return response()->download($zipFile);
//     // return Storage::get('files/8Iz3G4yvpD9D58vjR3Haph8lBDj9ZgyJN9quy8So.torrent');
// });
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
Route::post('items/download', 'DownloadItemController@download')->name('items.download');
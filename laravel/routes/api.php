<?php


use App\Http\Controllers\v1\NotebookController;
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

Route::group(['prefix' => 'notebook'], function() {
    Route::get('/',[NotebookController::class,'index']);
    Route::post('/',[NotebookController::class,'store'])->name('post');
    Route::get('/{notebook}',[NotebookController::class,'show']);
    Route::put('/{notebook}',[NotebookController::class,'update']);
    Route::delete('/{notebook}',[NotebookController::class,'destroy']);
});


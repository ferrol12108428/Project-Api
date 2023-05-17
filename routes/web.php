<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EskulController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/eskul', [EskulController::class, 'index']);

Route::post('/eskul/tambah-data', [EskulController::class, 'store']);

Route::get('/generate-token', [EskulController::class, 'createToken']);

Route::get('/eskul/{id}', [EskulController::class, 'show']);

Route::patch('/eskul/{id}/update', [EskulController::class, 'update']);

Route::delete('/eskul/{id}/delete', [EskulController::class, 'destroy']);

Route::get('/eskul/show/trash', [EskulController::class, 'trash']);

Route::get('/eskul/show/trash/{id}', [EskulController::class, 'restore']);

Route::get('/eskul/show/trash/permanent/{id}', [EskulController::class, 'permanent']);
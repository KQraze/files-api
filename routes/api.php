<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/authorization', [UserController::class, 'authorization']);
Route::post('/registration', [UserController::class, 'registration']);

Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('files')->group(function () {
    Route::post('/', [FileController::class, 'addFile'])->middleware('auth:sanctum');
    Route::patch('/{file_id}', [FileController::class, 'updateFile'])->middleware('auth:sanctum');
    Route::delete('/{file_id}', [FileController::class, 'deleteFile'])->middleware('auth:sanctum');
    Route::get('/{file_id}', [FileController::class, 'getFile'])->middleware('auth:sanctum');
    Route::get('/disk', [FileController::class, 'getDiskFiles'])->middleware('auth:sanctum');
    Route::get('/shared', [FileController::class, 'getSharedFiles'])->middleware('auth:sanctum');
});

/**
 * POST /files
 * PATCH /files/{file_id}
 * DELETE /files/{file_id}
 * GET /files/{file_id}
 * POST /files/{file_id}/accesses
 * DELETE /files/{file_id}/accesses
 * GET /files/disk
 * GET /files/shared
 */

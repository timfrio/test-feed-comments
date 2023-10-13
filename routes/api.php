<?php

use App\Http\Controllers\Api\CommentsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'controller'    => CommentsController::class,
    'prefix'        => 'comment'
], function () {
    Route::get('/', 'index');
    Route::get('/{parent}', 'show');
    Route::get('/{comment}/show', 'show');
    Route::post('/send-comment', 'create');
});

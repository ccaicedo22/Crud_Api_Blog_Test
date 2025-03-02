<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [AuthController::class, 'me'])->name('me');
});

Route::middleware(['jwt.verify'])->group(function () {
    
    // Rutas para Autores
    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('authors.index'); 
        Route::post('/', [AuthorController::class, 'store'])->name('authors.store'); 
        Route::get('/{id}', [AuthorController::class, 'show'])->name('authors.show'); 
        Route::put('/{id}', [AuthorController::class, 'update'])->name('authors.update'); 
        Route::delete('/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy'); 
    });

    // Rutas para Blogs 
    Route::prefix('blogs')->group(function () {
    });

});


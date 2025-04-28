<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/fetch', [UserController::class, 'fetch'])->name('users.fetch');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::post('/users/{id}/update', [UserController::class, 'update']);
Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);

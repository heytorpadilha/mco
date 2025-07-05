<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/create-user', [UserController::class, 'create'])
        ->name('user.create');

Route::post('/store-user', [UserController::class, 'store'])
        ->name('user.store');
/* listagem de usuários */
Route::get('/index-user', [UserController::class,'index'])
        ->name( 'user.index');
/* tela de editar usuário */
Route::get('/edit-user/{user}', [UserController::class,'edit'])
        ->name('user.edit');

/* ação para editar usuário */
Route::put('/update-user/{user}', [UserController::class,'update'])
        ->name('user.update');
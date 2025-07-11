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

/* tela de editar password do usuário */
Route::get('/edit-password-user/{user}', [UserController::class,'editPassword'])
        ->name('user.editPassword');

/* Ação de editar password do usuário */
Route::put('/update-password-user/{user}', [UserController::class,'updatePassword'])
        ->name('user.updatePassword');

/* tela de visualizar dados do usuário */
Route::get('/show-user/{user}', [UserController::class,'show'])
        ->name('user.show');

/* ação para deletar usuário */
Route::delete('/destroy-user/{user}', [UserController::class,'destroy'])
        ->name('user.destroy');
// rota para gerar pdf do usuário
Route::get('/generate-pdf-user/{user}', [UserController::class,'generatePdf'])
        ->name('user.generate-pdf');

// rota para gerar pdf da lista
Route::get('/generate-pdf-user/', [UserController::class,'generatePdfUsers'])
        ->name('user.generate-pdf-users');
<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UsersController::class, 'index'])->name('users.index');

Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');

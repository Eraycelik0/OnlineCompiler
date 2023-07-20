<?php

use App\Http\Controllers\CompilerController;
use Illuminate\Support\Facades\Route;


Route::get('/', [CompilerController::class, 'index']);
Route::post('/compile', [CompilerController::class, 'compile'])->name('compile');


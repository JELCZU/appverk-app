<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;

//Tworzenie nowego modułu
Route::post('/modules', [ModuleController::class, 'createModule']);

//  Pobieranie pliku ZIP z modułem
Route::get('/modules/{id}/download', [ModuleController::class, 'downloadModule']);

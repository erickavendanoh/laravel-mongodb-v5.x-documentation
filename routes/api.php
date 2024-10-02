<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Inicio. CON RESPECTO A SECCIÓN "Quick Start" DE DOCUMENTACIÓN OFICIAL

Route::resource('movies', MovieController::class)->only([
    'store'
]);

//Fin. CON RESPECTO A SECCIÓN "Quick Start" DE DOCUMENTACIÓN OFICIAL
<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/browse_movies', [MovieController::class, 'show']);


//Inicio. CON RESPECTO A SECCIÓN "Usage Examples" DE DOCUMENTACIÓN OFICIAL
Route::get('/find_a_document', [MovieController::class, 'findADocument']);
Route::get('/find_multiple_documents', [MovieController::class, 'findMultipleDocuments']);
Route::get('/insert_a_document', [MovieController::class, 'insertADocument']);
Route::get('/insert_multiple_documents', [MovieController::class, 'insertMultipleDocuments']);
Route::get('/update_a_document', [MovieController::class, 'updateADocument']);
Route::get('/update_multiple_documents', [MovieController::class, 'updateMultipleDocuments']);
Route::get('/delete_a_document', [MovieController::class, 'deleteADocument']);
Route::get('/delete_multiple_documents', [MovieController::class, 'deleteMultipleDocuments']);
Route::get('/count_documents', [MovieController::class, 'countDocuments']);
Route::get('/retrieve_distinct_field_values', [MovieController::class, 'retrieveDistinctFieldValues']);
//Esto ya no está incluido en app\Http\Controllers\MovieController.php
Route::get('/run_a_command', function(){
    $cursor = DB::connection('mongodb')
        ->command(['listCollections' => 1]);
    foreach ($cursor as $coll) {
        echo $coll['name'] . "<br>\n";
    }
});
//Fin. CON RESPECTO A SECCIÓN "Usage Examples" DE DOCUMENTACIÓN OFICIAL
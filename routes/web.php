<?php

use App\Http\Controllers\MovieController;
use App\Models\Concert;
use App\Models\Movie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Inicio. CON RESPECTO A SECCIÓN "Quick Start" DE DOCUMENTACIÓN OFICIAL

Route::get('/browse_movies', [MovieController::class, 'show']);

//Fin. CON RESPECTO A SECCIÓN "Quick Start" DE DOCUMENTACIÓN OFICIAL


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


//Inicio. CON RESPECTO A SECCIÓN "Fundamentals" DE DOCUMENTACIÓN OFICIAL

//Inicio. CON RESPECTO A SECCIÓN "Databases and Collections" DE DOCUMENTACIÓN OFICIAL

// list the collections in a database 
Route::get('/get_collections/{method}', function($method){
    //La BD a la que se hace referencia es la declarada en atributo 'database' dentro de la respectiva connection dentro del arreglo 'connections=>[]' (en este caso 'mongodb', que fue la definida en " 'default' => env('DB_CONNECTION'), ", cuyo valor de variable de entorno DB_CONNECTION en .env es "mongodb") en config\database.php
    if($method == 'listCollections'){
        $collections = DB::connection('mongodb')->getMongoDB()->listCollections(); //Empleando Query Builder. 
        //El método listCollections() devuelve un CollectioninfoIterator por lo que no se puede imprimir o recorrer directamente lo resultante en $collections, para hacerlo...
        foreach ($collections as $collection) {
            // Obtener el nombre de la colección
            $collectionName = $collection->getName();
            echo "Colección: " . $collectionName . "<br/>";
        }
    } 
    else if($method == 'getTablesListing'){
        Schema::getTablesListing(); //Al parecer no funciona
    } 
    else if($method == 'getTables'){
        $collections = Schema::getTables();
        foreach($collections as $collection){
            echo "Colección: " . $collection['name'] . "<br/>";
        }
    }
});

// list the fields in a collection 
Route::get('/get_columns', function(){
    $fields = Schema::getColumns('movies');
    foreach($fields as $field){
        echo "Campo: " . $field['name'] . "<br/>";
    }
});

// checks if the specified field exists in at least one document
Route::get('/has_column', function(){
    $hasColumn = Schema::hasColumn('movies', 'title');
    dd($hasColumn);
});

// checks if each specified field exists in at least one document
Route::get('/has_columns', function(){
    $hasColumn = Schema::hasColumns('movies', ['title', 'directors', 'cast']);
    dd($hasColumn);
});

//Fin. CON RESPECTO A SECCIÓN "Databases and Collections" DE DOCUMENTACIÓN OFICIAL

//Inicio. CON RESPECTO A SECCIÓN "Read Operations" DE DOCUMENTACIÓN OFICIAL

Route::get('/retrieve_documents_that_match_a_query', [MovieController::class, 'retrieveDocumentsThatMatchAQuery']);

Route::get('/match_array_field_elements/{way}', function($way){
    //This example retrieves documents in which the countries array is exactly
    if($way == 'extract_array_match'){
        $movies = Movie::where('countries', ['Indonesia', 'Canada'])
            ->get();
    }
    //This example retrieves documents in which the countries array contains one of the values in the array ['Mexico', 'USA']:
    else if($way == 'element_match'){
        $movies = Movie::whereIn('countries', ['Canada', 'Egypt'])
            ->get();
        //En documentación venía así, sin embargo no funcionaba:
        /*
        $movies = Movie::where('countries', 'in', ['Canada', 'Egypt'])
            ->get();
        */
    }
    foreach($movies as $movie){
        echo $movie->title . '<br/>';
    }
});

Route::get('/retrieve_all_documents_in_a_collection', function(){
    $movies = Movie::get();
    // $movies = Movie::all(); //lo mismo
    foreach($movies as $movie){
        echo $movie->title . '<br/>';
    }
});

Route::get('/search_text_fields', [MovieController::class, 'searchTextFields']);

Route::get('/skip_and_limit_results', [MovieController::class, 'skipAndLimitResults']);

Route::get('/sort_query_results', [MovieController::class, 'sortQueryResults']);

Route::get('/return_the_first_result', [MovieController::class, 'returnTheFirstResult']);

//Fin. CON RESPECTO A SECCIÓN "Read Operations" DE DOCUMENTACIÓN OFICIAL

//Inicio. CON RESPECTO A SECCIÓN "Write Operations" DE DOCUMENTACIÓN OFICIAL

Route::get('/insert_a_document_example', [MovieController::class, 'insertADocumentExample']);

Route::get('/insert_multiple_documents_example', [MovieController::class, 'insertMultipleDocumentsExample']);

Route::get('/update_a_document_example_first_way', [MovieController::class, 'updateADocumentExampleFirstWay']);

Route::get('/update_a_document_example_second_way', [MovieController::class, 'updateADocumentExampleSecondWay']);

Route::get('/update_multiple_documents_example', [MovieController::class, 'updateMultipleDocumentsExample']);

Route::get('/upsert_method', [MovieController::class, 'upsertMethod']);

Route::get('/update_method_with_upsert_option', [MovieController::class, 'updateMethodWithUpsertOption']);

Route::get('/sample_document_insetion_to_examples_of_update_arrays_in_a_document', function(){
    $concert = Concert::create([
        'performer' => 'Mitsuko Uchida',
        'genres' => ['classical', 'dance-pop'],
    ]);
    if($concert) echo 'success!';
});

Route::get('/add_values_to_an_array_example', [MovieController::class, 'addValuesToAnArrayExample']);

Route::get('/remove_values_from_an_array_example', [MovieController::class, 'removeValuesFromAnArrayExample']);

Route::get('/update_the_value_of_an_array_element_example', [MovieController::class, 'updateTheValueOfAnArrayElementExample']);

Route::get('/delete_a_document_example_first_way', [MovieController::class, 'deleteADocumentExampleFirstWay']);

Route::get('/delete_a_document_example_second_way', [MovieController::class, 'deleteADocumentExampleSecondWay']);

Route::get('/delete_a_document_example_third_way', [MovieController::class, 'deleteADocumentExampleThirdWay']);

Route::get('/delete_multiple_documents_example_first_way', [MovieController::class, 'deleteMultipleDocumentsExampleFirstWay']);

Route::get('/delete_multiple_documents_example_second_way', [MovieController::class, 'deleteMultipleDocumentsExampleSecondWay']);

//Fin. CON RESPECTO A SECCIÓN "Write Operations" DE DOCUMENTACIÓN OFICIAL

//Fin. CON RESPECTO A SECCIÓN "Fundamentals" DE DOCUMENTACIÓN OFICIAL
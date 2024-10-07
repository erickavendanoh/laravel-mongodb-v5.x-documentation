<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $movie = new Movie();
        $movie->fill($data);
        $movie->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return view('browse_movies', [
            'movies' => Movie::where('runtime', '<', 60)
                ->where('imdb.rating', '>', 8.5)
                ->orderBy('imdb.rating', 'desc')
                ->take(10)
                ->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }


    //Inicio. CON RESPECTO A SECCIÓN "Usage Examples" DE DOCUMENTACIÓN OFICIAL

    public function findADocument()
    {
        $movie = Movie::where('directors', 'Rob Reiner')
        ->orderBy('id')
        ->first();
        echo $movie->toJson();
    }

    public function findMultipleDocuments()
    {
        $movies = Movie::where('runtime', '>', 900)
        ->orderBy('id')
        ->get();
        return view('usage_examples.find_multiple_documents', compact('movies'));
    }

    public function insertADocument()
    {
        $movie = Movie::create([
            'title' => 'Marriage Story',
            'year' => 2019,
            'runtime' => 136,
        ]);
        
        echo $movie->toJson();
    }

    public function insertMultipleDocuments()
    {
        $success = Movie::insert([
            [
                'title' => 'Anatomy of a Fall',
                'release_date' => Carbon::createFromFormat('Y-m-d', '2023-08-23'),
            ],
            [
                'title' => 'The Boy and the Heron',
                'release_date' => Carbon::createFromFormat('Y-m-d', '2023-12-08'),
            ],
            [
                'title' => 'Passages',
                'release_date' => Carbon::createFromFormat('Y-m-d', '2023-06-28'),
            ],
        ]);
        
        echo 'Insert operation success: ' . ($success ? 'yes' : 'no');
    }

    public function updateADocument()
    {
        $updates = Movie::where('title', 'Carol')
            ->orderBy('id')
            ->first()
            ->update([
                'imdb' => [
                    'rating' => 7.3,
                    'votes' => 142000,
                ],
            ]);

        echo 'Updated documents: ' . $updates;
    }

    public function updateMultipleDocuments()
    {
        $updates = Movie::where('imdb.rating', '>', 9.0) //Equivalente a " { 'imdb.rating': { $gt: 9.0 } } " en filtro de MongoDB Atlas
            ->update(['acclaimed' => true]);

        echo 'Updated documents: ' . $updates;
    }

    public function deleteADocument()
    {
        $deleted = Movie::where('title', 'Quiz Show')
            ->orderBy('id')
            ->limit(1)
            ->delete();

        echo 'Deleted documents: ' . $deleted;
    }

    public function deleteMultipleDocuments()
    {
        $deleted = Movie::where('year', '<=', 1910) //Equivalente a " { year: { $lte: 1910 } } " en filtro de MongoDB Atlas
            ->delete();

        echo 'Deleted documents: ' . $deleted;
    }

    public function countDocuments()
    {
        $count = Movie::where('genres', 'Biography')
            ->count();
        echo 'Number of documents: ' . $count;
    }

    public function retrieveDistinctFieldValues()
    {
        $ratings = Movie::where('directors', 'Sofia Coppola')
            ->select('imdb.rating')
            ->distinct()
            ->get();
        echo $ratings;
    }

    //Fin. CON RESPECTO A SECCIÓN "Usage Examples" DE DOCUMENTACIÓN OFICIAL


    //Inicio. CON RESPECTO A SECCIÓN "Fundamentals" DE DOCUMENTACIÓN OFICIAL

    //Inicio. CON RESPECTO A SECCIÓN "Read Operations" DE DOCUMENTACIÓN OFICIAL

    public function retrieveDocumentsThatMatchAQuery()
    {
        $movies = Movie::where('year', 2010)
             ->where('imdb.rating', '>', 8.5)
             ->get();
         return view('browse_movies', [
             'movies' => $movies
         ]);
    }

    public function searchTextFields()
    {
        //A text search retrieves documents that contain a term or a phrase in the text-indexed fields (previously created). En sí como dice la documentación, se debió haber creado un text index en el campo plot de la colección "movies", pero como eso está después se omitió por ahora. Si realiza la búsqueda, pero es porque cuando se cargó el Sample Dataset la colección "movies" ya tenía un índice de tipo text index, que es compuesto y busca en campos cast, plot, genres y title cuando se emplea operador $search, por lo que si busca en base a ese texto pero no solo será con respecto a campo plot, sino que también con respecto a los otros.
            $movies = Movie::where('$text', ['$search' => '"love story"'])
            ->orderBy('score', ['$meta' => 'textScore']) //a text search assigns a numerical text score to indicate how closely each result matches the string in your query filter. You can sort the results by relevance by using the orderBy() method to sort on the textScore metadata field. You can access this metadata by using the $meta operator
            ->get();
        return view('browse_movies', [
        'movies' => $movies
        ]);
    }

    public function skipAndLimitResults()
    {
        $movies = Movie::where('year', 1999)
             ->skip(2)
             ->take(3)
             ->get();
         return view('browse_movies', [
             'movies' => $movies
         ]);
    }

    public function sortQueryResults()
    {
        $movies = Movie::where('countries', 'Indonesia')
            ->orderBy('year')
            ->orderBy('title', 'desc')
            ->get();
        return view('browse_movies', [
            'movies' => $movies
        ]);
    }

    public function returnTheFirstResult()
    {
        $movie = Movie::where('runtime', 30)
            ->orderBy('_id')
            ->first();
        echo $movie;
    }

    //Fin. CON RESPECTO A SECCIÓN "Read Operations" DE DOCUMENTACIÓN OFICIAL

    //Inicio. CON RESPECTO A SECCIÓN "Write Operations" DE DOCUMENTACIÓN OFICIAL

    //No existía la colección "concerts" en BD "sample_mflix" (que es a la que se hace referencia en config\database.php en connection "mongodb" como indicaba documentación) pero se creará con esta primera inserción
    public function insertADocumentExample()
    {
        $concert = new Concert();
        $concert->performer = 'Mitsuko Uchida';
        $concert->venue = 'Carnegie Hall';
        $concert->genres = ['classical'];
        $concert->ticketsSold = 2121;
        $concert->performanceDate = Carbon::create(2024, 4, 1, 20, 0, 0, 'EST');
        $concert->save();

        $insertedId = $concert->id;
        echo 'Success! Inserted id: '. $insertedId;
    }

    public function insertMultipleDocumentsExample()
    {
        $data = [
            [
                'performer' => 'Brad Mehldau',
                'venue' => 'Philharmonie de Paris',
                'genres' => [ 'jazz', 'post-bop' ],
                'ticketsSold' => 5745,
                'performanceDate' => Carbon::createFromFormat('Y-m-d H:i:s', '2025-02-12 20:00:00', 'CET'),
            ],
            [
                'performer' => 'Billy Joel',
                'venue' => 'Madison Square Garden',
                'genres' => [ 'rock', 'soft rock', 'pop rock' ],
                'ticketsSold' => 12852,
                'performanceDate' => Carbon::createFromFormat('Y-m-d H:i:s', '2025-02-12 20:00:00', 'CET'),
            ],
        ];
        
        $result = Concert::insert($data);
        if($result == 1) echo 'Success!';
    }

    public function updateADocumentExampleFirstWay()
    {
        $concert = Concert::first();
        echo 'document before the update: ' . $concert . '<br/>';
        $concert->venue = 'Manchester Arena';
        $concert->ticketsSold = 9543;
        $concert->save();
        echo 'document after the update: ' . $concert;
    }

    public function updateADocumentExampleSecondWay()
    {
        $concert = Concert::where(['performer' => 'Brad Mehldau'])
            ->orderBy('id')
            ->first()
            ->update(['venue' => 'Manchester Arena', 'ticketsSold' => 9543]);
            if($concert == 1) echo 'Success!';
    }

    public function updateMultipleDocumentsExample()
    {
        $result = Concert::whereIn('venue', ['Philharmonie de Paris', 'Soldier Field'])
            ->update(['venue' => 'Concertgebouw', 'ticketsSold' => 0]);
        echo 'updated documents: ' . $result;
    }

    //upsert actualiza el documento si existe o lo inserta en caso de que no exista
    //hay dos maneras, con método upsert(), y con update() pasándole como opción (parámetro) "['upsert' => true]"
    //-upsert()
    /*
    YourModel::upsert(
        [* documents to update or insert *],
        '* unique field *',
        [* fields to update *],
    );
    */
    public function upsertMethod()
    {
        //Aunque en el documento o documentos correspondientes al primer parámetro, vengan valores distintos en otros campos de documentos ya existentes, solo se actualizarán aquellos declarados en el tercer parámetro.
        $result = Concert::upsert(
            [
            ['performer' => 'Angel Olsen', 'venue' => 'Academy of Music', 'ticketsSold' => 275],
            ['performer' => 'Darondo', 'venue' => 'Cafe du Nord', 'ticketsSold' => 300],
        ], 
        'performer', 
        ['ticketsSold']);
        if($result) echo 'success! result: ' . $result;
    }
    //-update() with upsert option to true
    /*
    YourModel::where(* match criteria *)
        ->update(
        [* update data *],
        ['upsert' => true]);
    */
    public function updateMethodWithUpsertOption()
    {
        $result = Concert::where(['performer' => 'Jon Batiste', 'venue' => 'Radio City Music Hall'])
        ->update(
            ['genres' => ['R&B', 'soul'], 'ticketsSold' => 4000],
            ['upsert' => true],
        );
        if($result) echo 'success!';
    }

    //Arrays
    //-add
    /*
    YourModel::where(<match criteria>)
        ->push(
            <field name>,
            [<values>], // array or single value to add
            unique: true); // whether to skip existing values
    */
    public function addValuesToAnArrayExample()
    {
        Concert::where('performer', 'Mitsuko Uchida')
        ->push(
            'genres',
            ['baroque'],
        );
        return "1";
    }
    //-remove
    /*
    YourModel::where(<match criteria>)
        ->pull(
            <field name>,
            [<values>]); // array or single value to remove
    */
    public function removeValuesFromAnArrayExample()
    {
        Concert::where('performer', 'Mitsuko Uchida')
        ->pull(
            'genres',
            ['dance-pop', 'classical'],
        );
        return "1";
    }
    //-update
    //Currently, the Laravel Integration offers this operation only on the DB facade and not on the Eloquent ORM.
    /*
    DB::connection('mongodb')
        ->getCollection(<collection name>)
        ->updateOne(
            <match criteria>,
            ['$set' => ['<array field>.$' => <replacement value>]]);
    */
    //Se borro el documento correspondiente a 'performer' => 'Mitsuko Uchida' y se volvió a ejecutar lo de ruta /sample_document_insetion_to_examples_of_update_arrays_in_a_document para poder hacer lo de esta función
    public function updateTheValueOfAnArrayElementExample()
    {
        //The $ operator represents the first array element that matches the query
        $match = ['performer' => 'Mitsuko Uchida', 'genres' => 'dance-pop'];
        $update = ['$set' => ['genres.$' => 'contemporary']];
        DB::connection('mongodb')
            ->getCollection('concerts')
            ->updateOne($match, $update);
        return "1";
    }

    public function deleteADocumentExampleFirstWay()
    {
        $concert = Concert::first();
        $documentsDeleted = $concert->delete();
        if($documentsDeleted) echo 'success!';
    }

    public function deleteADocumentExampleSecondWay()
    {
        $id = '67043b5cbdddccbdb905cd54';
        $documentsDeleted = Concert::destroy($id);
        echo 'documents deleted: ' . $documentsDeleted;
    }

    public function deleteADocumentExampleThirdWay()
    {
        $documentsDeleted = Concert::where('venue', 'Carnegie Hall')
        ->limit(1)
        ->delete();
        if($documentsDeleted) echo 'success!';
    }

    public function deleteMultipleDocumentsExampleFirstWay()
    {
        $documentsDeleted = $ids = ['670452048975130903c04e88', '670452048975130903c04e89'];
        Concert::destroy($ids);
        echo 'documents deleted: ' . count($documentsDeleted);
        //NOTA: The destroy() method performance suffers when passed large lists. For better performance, use Model::whereIn('id', $ids)->delete() instead.
    }

    public function deleteMultipleDocumentsExampleSecondWay()
    {
        $documentsDeleted = Concert::where('ticketsSold', '>', 290)
            ->delete();
        if($documentsDeleted) echo 'success!';
    }

    //Fin. CON RESPECTO A SECCIÓN "Write Operations" DE DOCUMENTACIÓN OFICIAL

    //Fin. CON RESPECTO A SECCIÓN "Fundamentals" DE DOCUMENTACIÓN OFICIAL

}

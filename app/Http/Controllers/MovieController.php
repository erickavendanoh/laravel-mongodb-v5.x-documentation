<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    //Fin. CON RESPECTO A SECCIÓN "Fundamentals" DE DOCUMENTACIÓN OFICIAL

}

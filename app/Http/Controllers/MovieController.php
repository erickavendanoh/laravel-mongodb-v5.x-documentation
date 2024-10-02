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
}

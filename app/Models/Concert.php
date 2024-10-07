<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = ['performer', 'venue', 'genres', 'ticketsSold', 'performanceDate'];
    protected $casts = ['performanceDate' => 'datetime']; //The casts method (en este caso está como atributo de la clase, pero es igual que si se definiera un método "casts()" que retorne el array al que se está igualando acá) should return an array where the key is the name of the attribute being cast and the value is the type you wish to cast the column to.
}

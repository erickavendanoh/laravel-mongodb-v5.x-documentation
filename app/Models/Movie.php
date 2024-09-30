<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = ['title', 'year', 'runtime', 'imdb', 'plot'];
}

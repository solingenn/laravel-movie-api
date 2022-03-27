<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieDetail extends Model
{
    use HasFactory;

    protected $table = 'movie_details';
    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'person_id',
        'role_id',
        'character_name'
    ];
}
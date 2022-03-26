<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Movie;

class Role extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'role_name'
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'movie_details')->withPivot('id', 'character_name');
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_details')->withPivot('id', 'character_name');
    }
}

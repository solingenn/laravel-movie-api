<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use App\Models\Role;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $dates = ['born'];
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'born'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_details')->withPivot('id', 'character_name');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'movie_details')->withPivot('id','character_name');
    }
}
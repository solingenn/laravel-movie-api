<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Role;

class Movie extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'title', 
        'release_year',
        'description',
    ];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'movie_details')->withPivot('id', 'role_id', 'character_name');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'movie_details')->withPivot('id', 'role_id', 'character_name');
    }
}
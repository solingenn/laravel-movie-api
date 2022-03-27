<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run()
    {
        Movie::create([
            'title' => 'Kingdom of Heaven',
            'release_year' => '2005',
            'description' => 'Balian of Ibelin travels to Jerusalem during the Crusades of the 12th century....',
        ]);

        Movie::create([
            'title' => 'The Man from Earth',
            'release_year' => '2007',
            'description' => 'An impromptu goodbye party for Professor John Oldman becomes a mysterious interrogation....',
        ]);

        Movie::create([
            'title' => 'The Art of Racing in the Rain',
            'release_year' => '2019',
            'description' => 'Through his bond with his owner, aspiring Formula One race car driver Denny, golden retriever Enzo learns that the techniques....',
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;

class PersonSeeder extends Seeder
{
    public function run()
    {
        Person::create([
            'first_name' => 'Ridley',
            'last_name' => 'Scott',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'William',
            'last_name' => 'Monahan',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Orlando',
            'last_name' => 'Bloom',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Eva',
            'last_name' => 'Green',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Liam',
            'last_name' => 'Neeson',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Nikolaj',
            'last_name' => 'Coster-Waldau',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Jeremy',
            'last_name' => 'Irons',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Harry',
            'last_name' => 'Gregson-Williams',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Richard',
            'last_name' => 'Schenkman',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Jerome',
            'last_name' => 'Bixby',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'David',
            'last_name' => 'Smith',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Tony',
            'last_name' => 'Todd',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'John',
            'last_name' => 'Billingsley',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Annika',
            'last_name' => 'Peterson',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'William',
            'last_name' => 'Katt',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Mark',
            'last_name' => 'Hinton Stewart',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Kevin',
            'last_name' => 'Costner',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Milo',
            'last_name' => 'Ventmiglia',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Simon',
            'last_name' => 'Curtis',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Garth',
            'last_name' => 'Stein',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Amanda',
            'last_name' => 'Seyfried',
            'born' => '2000-01-01',
        ]);

        Person::create([
            'first_name' => 'Gary',
            'last_name' => 'Cole',
            'born' => '2000-01-01',
        ]);
    }
}
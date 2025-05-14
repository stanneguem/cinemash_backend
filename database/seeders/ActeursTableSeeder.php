<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acteur;
use Illuminate\Support\Facades\DB;

class ActeursTableSeeder extends Seeder
{
    public function run()
    {
        // Désactive la vérification des contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Acteur::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $acteurs = [
            [
                'nom' => 'DiCaprio',
                'photo_URL' => 'https://example.com/actors/dicaprio.jpg',
                'biographie' => 'Leonardo Wilhelm DiCaprio, né le 11 novembre 1974 à Los Angeles, est un acteur, producteur de cinéma et environnementaliste américain.'
            ],
            [
                'nom' => 'Johansson',
                'photo_URL' => 'https://example.com/actors/johansson.jpg',
                'biographie' => 'Scarlett Ingrid Johansson, née le 22 novembre 1984 à New York, est une actrice, chanteuse et productrice américaine.'
            ],
            [
                'nom' => 'Depp',
                'photo_URL' => 'https://example.com/actors/depp.jpg',
                'biographie' => 'John Christopher Depp II, dit Johnny Depp, né le 9 juin 1963 à Owensboro (Kentucky), est un acteur, réalisateur, guitariste, scénariste et producteur de cinéma américain.'
            ],
            [
                'nom' => 'Lawrence',
                'photo_URL' => 'https://example.com/actors/lawrence.jpg',
                'biographie' => 'Jennifer Shrader Lawrence, née le 15 août 1990 à Indian Hills, est une actrice américaine.'
            ],
            [
                'nom' => 'Smith',
                'photo_URL' => 'https://example.com/actors/smith.jpg',
                'biographie' => 'Willard Carroll Smith Jr., dit Will Smith, né le 25 septembre 1968 à Philadelphie, est un acteur, chanteur, producteur de cinéma et rappeur américain.'
            ]
        ];

        foreach ($acteurs as $acteur) {
            Acteur::create($acteur);
        }
    }
}

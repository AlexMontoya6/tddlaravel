<?php

namespace Database\Seeders;

use Illuminate\{Database\Seeder};
use App\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Profession::create([
            'title' => 'Desarrollador Back-End'
        ]);

        Profession::create([
            'title' => 'Desarrollador Front-End'
        ]);

        Profession::create([
            'title' => 'Diseñador web'
        ]);

        factory(Profession::class, 17)->create();
    }
}

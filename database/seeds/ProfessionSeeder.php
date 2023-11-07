<?php

use App\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}

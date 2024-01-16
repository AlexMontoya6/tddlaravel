<?php
namespace Database\Seeders;

use App\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class)->create([
            'name' => 'IES Ingeniero',
        ]);

        factory(Team::class)->times(99)->create()->unique();
    }
}

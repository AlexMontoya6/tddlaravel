<?php

use App\Profession;
use App\Skill;
use App\Team;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $professions;
    private $skills;
    private $teams;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->fetchRelations();

        $this->createAdmin();

        foreach (range(1, 999) as $i) {
            $this->createRandomUser();
        }
    }

    private function fetchRelations()
    {
        $this->professions = Profession::all();
        $this->skills = Skill::all();
        $this->teams = Team::all();
    }

    private function createAdmin()
    {
        $user = factory(User::class)->create([
            'team_id' => $this->teams->firstWhere('name', 'IES Ingeniero')->id,
            'name' => 'Pepe Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'created_at' => now()->addDay(),
        ]);

        $user->profile()->create([
            'bio' => 'Programador',
            'profession_id' => $this->professions->where('title', 'Desarrollador Back-End')->first()->id,
        ]);
    }

    private function createRandomUser(): void
    {
        $user = factory(User::class)->create([
            'team_id' => rand(0, 2) ? $this->teams->random()->id : null,
        ]);

        $numSkills = $this->skills->count();

        $user->skills()->attach($this->skills->random(rand(0, $numSkills)));

        $user->profile()->create(
            factory(\App\UserProfile::class)->raw([
                'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
            ])
        );
    }
}

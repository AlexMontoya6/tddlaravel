<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Skill::class)->create(['name' => 'HTML']);
        factory(\App\Skill::class)->create(['name' => 'CSS']);
        factory(\App\Skill::class)->create(['name' => 'JS']);
        factory(\App\Skill::class)->create(['name' => 'PHP']);
        factory(\App\Skill::class)->create(['name' => 'SQL']);
        factory(\App\Skill::class)->create(['name' => 'POO']);
        factory(\App\Skill::class)->create(['name' => 'TDD']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables(['skill_user','professions', 'users','skills','user_profiles','teams']);

        $this->call(ProfessionSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(UserSeeder::class);
    }

    private function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

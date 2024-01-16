<?php
namespace Database\Seeders;
use App\Profession;
use App\Skill;
use App\Team;
use App\User;
use App\UserProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $professions;
    private $skills;
    private $teams;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->fetchRelations(); // Asigna las relaciones

        $this->createAdmin(); // Crea un admin

        // Crea 999 usuarios, llamando al metodo de creacion 999 veces
        foreach (range(1, 999) as $i) {
            $this->createRandomUser();
        }
    }

    /**
     * Hace las relaciones de los atributos
     * Es una forma de ahorrar memoria ya que si se inicia
     * este seeder solo coge la info de las tablas cuando se
     * llama a este método
     * @return void
     */
    private function fetchRelations()
    {
        $this->professions = Profession::all();
        $this->skills = Skill::all();
        $this->teams = Team::all();
    }

    /**
     * Crea un usuario administrador
     * @return void
     */
    private function createAdmin()
    {
        $user = factory(User::class)->create([
            'team_id' => $this->teams->firstWhere('name', 'IES Ingeniero')->id,
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'created_at' => now(),
            'active' => true,
        ]);
    }

    /**
     * Crea un usuario al azar
     * @return void
     */
    private function createRandomUser()
    {
        /** Crea un usuario con una empresa al azar si el numero del rand es mayor
         * que 0, si es cero no tiene empresa, asi hay usuarios con empresas y otros que
         * no tienen
         */
        $user = factory(User::class)->create([
            'team_id' => rand(0, 2) ? $this->teams->random()->id : null,
            'active' => rand(0, 4) ? true : false,
            'created_at' => now()->subDays(rand(1, 90)), // Le resta un numero aleatorio de dias a la fecha actual
        ]);

        /** Saca el numero total de habilidades para despues sacar unas habilidades al azar
         * entre el 0 y ese numero de habilidades totales, si hay 7 habilidaes y el numero
         * del rand es 4, se coje 4 habilidades al azar de todas las que hay
         */
        $numSkills = $this->skills->count(); // Saca el numero total de habilidades
        $user->skills()->attach($this->skills->random(rand(0, $numSkills))); // Le asigna al user habilidades al azar

        /** Se crean 10 instancias de la clase Login asociados a ese usuario, es decir
         * el usuario se loguea 10 veces pero solo se coge el ultimo inicio de sesion
         */
        factory(\App\Login::class)->times(1, 10)->create([
            'user_id' => $user->id,
        ]);
    }
}

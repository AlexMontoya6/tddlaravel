<?php

use App\UserProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'role' => 'user',
        'active' => true,
        'remember_token' => Str::random(10),
    ];
});

//** Crea un perfil al usuario en cuanto se crea */
$factory->afterCreating(App\User::class, function ($user) {
    $user->profile()->save(factory(UserProfile::class)->make());
});

/** Cuando state es inactive, se establece al active false
 * state es un metodo que tiene predefinido Laravel para definir
 * estados personalizados
 */
$factory->state(\App\User::class, 'inactive', function ($faker) {
    return ['active' => false];
});

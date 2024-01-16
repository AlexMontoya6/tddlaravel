<?php

use Faker\Generator as Faker;

$factory->define(App\Profession::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3), // Devuelve un string de 3 palabras
    ];
});

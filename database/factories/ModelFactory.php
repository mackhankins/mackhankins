<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(MH\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(MH\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'pcontent' => $faker->paragraphs(6, true),
        'user_id' => 1,
        'featuredimage' => 'laravel.jpg',
        'status' => 'published',
        'imgsrc' => 'laracasts.com',
        'excerpt' => $faker->paragraphs(3, true),
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Match::class, function (Faker $faker) {
    return [
        'next' => strval($faker->numberBetween(0, 2)),
        'winner' => strval($faker->numberBetween(0, 2)),
        'combination' => strval($faker->numberBetween(0, 8)),
    ];
});

$factory->state(App\Match::class, 'zero', function (Faker $faker) {
    return [
        'next' => '0',
        'winner' => '0',
        'combination' => '0',
    ];
});

$factory->state(App\Match::class, 'win-x', function (Faker $faker) {
    return [
        'next' => '0',
        'winner' => '1',
        'combination' => strval($faker->numberBetween(1, 8)),
    ];
});

$factory->state(App\Match::class, 'win-o', function (Faker $faker) {
    return [
        'next' => '0',
        'winner' => '2',
        'combination' => strval($faker->numberBetween(1, 8)),
    ];
});

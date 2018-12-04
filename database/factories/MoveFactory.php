<?php

use Faker\Generator as Faker;

$factory->define(App\Move::class, function (Faker $faker) {
    return [
        'move' => $faker->numberBetween(1, 2),
        'position' => $faker->numberBetween(0, 8),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'match_id' => function () {
            return factory(App\Match::class)->create()->id;
        },
    ];
});

$factory->state(App\Move::class, 'move-1' ,function (Faker $faker) { return [ 'move' => '1']; });
$factory->state(App\Move::class, 'move-2' ,function (Faker $faker) { return [ 'move' => '2']; });

$factory->state(App\Move::class, 'position-0' ,function (Faker $faker) { return [ 'position' => '0']; });
$factory->state(App\Move::class, 'position-1' ,function (Faker $faker) { return [ 'position' => '1']; });
$factory->state(App\Move::class, 'position-2' ,function (Faker $faker) { return [ 'position' => '2']; });
$factory->state(App\Move::class, 'position-3' ,function (Faker $faker) { return [ 'position' => '3']; });
$factory->state(App\Move::class, 'position-4' ,function (Faker $faker) { return [ 'position' => '4']; });
$factory->state(App\Move::class, 'position-5' ,function (Faker $faker) { return [ 'position' => '5']; });
$factory->state(App\Move::class, 'position-6' ,function (Faker $faker) { return [ 'position' => '6']; });
$factory->state(App\Move::class, 'position-7' ,function (Faker $faker) { return [ 'position' => '7']; });
$factory->state(App\Move::class, 'position-8' ,function (Faker $faker) { return [ 'position' => '8']; });

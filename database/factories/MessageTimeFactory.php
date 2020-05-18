<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MessageTime;
use Faker\Generator as Faker;

$factory->define(MessageTime::class, function (Faker $faker) {
    return [
        //
        'start_at' => $faker->time('H:m:00')
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Folder;
use Faker\Generator as Faker;

$factory->define(Folder::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'folder_id' => 1,
        'name' => $faker->lexify('Folder ?????'),
    ];
});

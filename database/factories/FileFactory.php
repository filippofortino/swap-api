<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    // $extensions = ['txt', 'zip', 'rar', 'avi', 'mp4', '']
    $filename = "{$faker->md5}.{$faker->fileExtension}";
    $preview = null;
    if(rand(0, 1)) {
        $filename = "{$faker->md5}.jpg";
        $preview = $faker->imageUrl(640, 800, 'nature');
    }
    return [
        'uuid' => $faker->uuid,
        'folder_id' => null,
        'name' => $filename,
        'preview' => $preview,
        'info' => null,
        'hash' => $faker->sha256
    ];
});

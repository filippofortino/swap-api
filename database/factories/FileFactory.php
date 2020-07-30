<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    $filename = "{$faker->md5}.{$faker->fileExtension}";
    $preview = null;
    if(rand(0, 1)) {
        $filename = "{$faker->md5}.jpg";
        $preview = $faker->imageUrl(640, 800, 'nature');
    }

    return [
        'uuid' => $faker->uuid,
        'folder_id' => 1,
        'name' => $filename,
        'preview' => $preview,
        'mime_type' => $faker->mimeType,
        'size' => rand(500, 350000),
        'hash' => $faker->sha256
    ];
});

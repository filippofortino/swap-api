<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    $filename = "{$faker->md5}.{$faker->fileExtension}";
    $preview = null;
    $mime = $faker->mimeType;
    if(rand(0, 1)) {
        $filename = "{$faker->md5}.jpg";
        $image_id = (string)rand(1, 1100);
        $preview = "https://picsum.photos/id/{$image_id}/640/800";
        // $preview = $faker->imageUrl(640, 800, 'nature');
        $mime = 'image/jpeg';
    }

    return [
        'uuid' => $faker->uuid,
        'folder_id' => 1,
        'name' => $filename,
        'path' => $filename,
        'preview' => $preview,
        'mime_type' => $mime,
        'size' => rand(500, 350000),
        'hash' => $faker->sha256
    ];
});

<?php

use Chrisjk123\Blogger\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $title = $faker->name;

    return [
        'title' => $title,
        'slug' => Str::slug($title),
        'parent_id' => null,
    ];
});

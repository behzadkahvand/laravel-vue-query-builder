<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()->create([
            "id" => "first-post",
            "views" => 100,
        ]);
        Post::factory()->create([
            "id" => "second-post",
            "views" => 75,
        ]);
        Post::factory()->create([
            "id" => "third-post",
            "views" => 250,
        ]);
        Post::factory()->create([
            "id" => "fourth-post",
            "views" => 120,
        ]);
        Post::factory()->create([
            "id" => "fifth-post",
            "views" => 50,
            "timestamp" => 1043157345
        ]);
    }
}

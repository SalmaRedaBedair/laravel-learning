<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Post::factory(1000)->create();
        for ($i = 0; $i < 1000; $i++) {
            DB::table('posts')->insert(
                [
                    'title' => fake()->word(),
                    'content' => fake()->text(45),
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Article;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Article::factory()
            ->count(10)
            ->create();
    }
}
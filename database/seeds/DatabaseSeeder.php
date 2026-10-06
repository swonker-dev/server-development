<?php

use App\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $moderatorRole = Role::firstOrCreate([
            'name' => 'moderator',
        ]);

        $readerRole = Role::firstOrCreate([
            'name' => 'reader',
        ]);

        User::updateOrCreate(
            [
                'email' => 'moderator@example.com',
            ],
            [
                'name' => 'Moderator',
                'password' => Hash::make('12345678'),
                'role_id' => $moderatorRole->id,
            ]
        );

        User::whereNull('role_id')
            ->update([
                'role_id' => $readerRole->id,
            ]);

        if (Article::count() === 0) {
            Article::factory()
                ->count(10)
                ->create();
        }
    }
}
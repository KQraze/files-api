<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->state(new Sequence(
            ['email' => 'user1@api.ru', 'password' => 'Qa2', 'first_name' => 'user1', 'last_name' => 'user1'],
            ['email' => 'user2@api.ru', 'password' => 'As1', 'first_name' => 'user2', 'last_name' => 'user2'],
        ))->create();

        Role::factory(2)->state(new Sequence(
            ['type' => 'author'],
            ['type' => 'co-author'],
        ))->create();
    }
}

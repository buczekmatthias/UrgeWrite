<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => Hash::make('test'),
        ]);

        $user->noteGroups()->create(['name' => 'Unset']);
        $user->taskGroups()->create(['name' => 'Unset']);
    }
}

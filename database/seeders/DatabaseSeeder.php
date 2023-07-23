<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Ali Nauf';
        $user->email = 'nauf@live.com';
        $user->password = bcrypt('Test@123');
        $user->save();
        // \App\Models\User::factory(10)->create();

         $this->call([
             KnowledgeBaseSeeder::class,
         ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

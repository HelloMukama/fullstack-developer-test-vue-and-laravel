<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Jane Doe using the janedoe state method
        User::factory()->janedoe()->create();

        // Create regular users
        User::factory()->count(9)->create();

        // Create other models
        \App\Models\Tag::factory(4)->create();
        \App\Models\Project::factory(5)->create();
        \App\Models\Task::factory(20)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        Task::factory()->count(10)->create();
        Comment::factory()->count(10)->create();
    }
}

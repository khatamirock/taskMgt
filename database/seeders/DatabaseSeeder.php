<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $member = User::factory()->create([
            'name' => 'John Member',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'member'
        ]);

        $project1 = \App\Models\Project::create([
            'name' => 'Website Redesign',
            'description' => 'Revamp the corporate website with modern aesthetics.',
            'created_by' => $admin->id
        ]);

        $project2 = \App\Models\Project::create([
            'name' => 'Mobile App',
            'description' => 'Develop a cross-platform mobile application.',
            'created_by' => $admin->id
        ]);

        \App\Models\Task::create([
            'title' => 'Design System',
            'description' => 'Create a comprehensive design system.',
            'status' => 'done',
            'project_id' => $project1->id,
            'assigned_to' => $admin->id
        ]);

        \App\Models\Task::create([
            'title' => 'Landing Page UI',
            'description' => 'Design the main landing page.',
            'status' => 'in_progress',
            'project_id' => $project1->id,
            'assigned_to' => $member->id
        ]);

        \App\Models\Task::create([
            'title' => 'API Auth logic',
            'description' => 'Implement Sanctum authentication.',
            'status' => 'todo',
            'project_id' => $project2->id,
            'assigned_to' => $admin->id
        ]);
    }
}

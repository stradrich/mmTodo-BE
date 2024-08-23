<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    public function run()
    {
        Task::create([
            'title' => 'test',
            'description' => 'test',
            'status' => 'incomplete',
            'due_date' => now()->addDays(1),
            'priority' => 'low',
        ]);

        Task::create([
            'title' => 'test1',
            'description' => 'test1',
            'status' => 'complete',
            'due_date' => now()->addDays(2),
            'priority' => 'medium',
        ]);

        Task::create([
            'title' => 'test2',
            'description' => 'test2',
            'status' => 'incomplete',
            'due_date' => now()->addDays(3),
            'priority' => 'high',
        ]);
    }
}


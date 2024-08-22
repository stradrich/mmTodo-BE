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
            'priority' => 'low',
            'due_date' => now()->addDays(1),
        ]);

        Task::create([
            'title' => 'test1',
            'description' => 'test1',
            'status' => 'complete',
            'priority' => 'mid',
            'due_date' => now()->addDays(2),
        ]);

        Task::create([
            'title' => 'test2',
            'description' => 'test2',
            'status' => 'incomplete',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
        ]);
    }
}


<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TaskTableSeeder::class);
    }

    public function test_can_retrieve_all_tasks()
    {

        $response = $this->get('/tasks');
        // dd($response->json());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['title', 'description', 'status', 'due_date', 'priority'],
        ]);
    }

    public function test_can_create_task()
    {
        $taskData = [
            'title' => 'unit test',
            'description' => 'unit test',
            'status' => 'incomplete',
            'due_date' => '2024-08-30',
            'priority' => 'medium',
        ];

        $response = $this->postJson('/tasks', $taskData);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'task' => [
                'id',
                'title',
                'description',
                'status',
                'due_date',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);

        // Check if the returned task data matches the input data (excluding timestamps)
        $response->assertJson([
            'message' => 'Task created successfully',
            'task' => [
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'due_date' => $taskData['due_date'] . 'T00:00:00.000000Z',
                'priority' => $taskData['priority'],
            ],
        ]);
    }

    public function test_can_update_task()
    {

    }

    public function test_can_delete_task()
    {

    }

}

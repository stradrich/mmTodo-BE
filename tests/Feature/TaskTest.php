<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions,  WithoutMiddleware;

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
            'title' => 'create unit test',
            'description' => 'create unit test',
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
        // Fetch an existing task from the seeded data
        $task = Task::find(1); // Adjust the ID if needed
        // dump($task);
        $this->assertEquals('test', $task->title);

        // Prepare update data
        $taskData = [
            'title' => 'update unit test',
            'description' => 'update unit test',
            'status' => 'incomplete',
            'due_date' => '2024-08-30',
            'priority' => 'medium',
        ];

        // Perform the PUT request to update the task
        $response = $this->putJson("/tasks/{$task->id}", $taskData);
        // dump($response->json());

        // Assert that the status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON structure and content
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

        // Check if the returned task data matches the input data
        $response->assertJson([
            'message' => 'Task updated successfully',
            'task' => [
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'due_date' => $taskData['due_date'] . 'T00:00:00.000000Z',
                'priority' => $taskData['priority'],
            ],
        ]);
        dump($response->json());

        // Fetch the updated task from the database
        $updatedTask = Task::find($task->id);
        // dump($updatedTask);
        // Assert that the task was updated in the database
        $this->assertEquals($taskData['title'], $updatedTask->title);
        $this->assertEquals($taskData['description'], $updatedTask->description);
        $this->assertEquals($taskData['status'], $updatedTask->status);
        $this->assertEquals($taskData['due_date'], $updatedTask->due_date->format('Y-m-d'));
        $this->assertEquals($taskData['priority'], $updatedTask->priority);
    }




    public function test_can_delete_task()
    {

    }

}

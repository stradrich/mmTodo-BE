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
        dd($response->json());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['title', 'description', 'status', 'due_date', 'priority'],
        ]);
    }

    public function test_can_create_task()
    {

    }

    public function test_can_update_task()
    {

    }

    public function test_can_delete_task()
    {

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class TaskController extends Controller
{
    // Retrieve all tasks
    public function index()
    {
        $tasks = Task::all();

        return response()->json(Task::all());
    }

    // Create (Show form to create a new task):
    public function create()
    {
        return view('tasks.create');
    }

    // Store (Save the new task):
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:incomplete,complete',
            'due_date' => 'required|date',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $task = Task::create($request->all());

        // react
        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], 201);
    }

    // Show (View a specific task):
    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);
            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }


    // Edit (Show form to edit an existing task):
    public function edit(Task $task)
    {
        return view('tasks.edit',compact('task'));
    }

    // Update (Save the edited task):
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        Log::info('Before Update:', $task->toArray());

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:incomplete,complete',
            'due_date' => 'required|date',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        // $task->update($request->all());

         // Use fill to update the task
        $task->fill($request->only([
            'title', 'description', 'status', 'due_date', 'priority'
        ]))->save();

        Log::info('After Update:', $task->toArray());

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    // Destroy (Delete a task):
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }

    // Destroy (Delete all tasks)
    public function destroyAll() {
        Task::truncate();

        return response()->json([
            'message' => 'All tasks deleted successfully'
        ]);
    }
}

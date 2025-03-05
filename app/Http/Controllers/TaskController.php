<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class TaskController extends BaseController
{
    // Get all public posts with user details
    public function getPublicPosts()
    {
        $tasks = DB::table('tasks')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->select(
                'tasks.id as task_id',
                'tasks.title',
                'tasks.description',
                'tasks.image_url',
                'tasks.created_at as task_created_at',
                'users.name as user_name',
                'users.image as user_image'
            )
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    // Get all tasks for a specific user
    public function view(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $tasks = Task::where('user_id', $userId)->get();

        return response()->json(['tasks' => $tasks]);
    }

    // Create a new task
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('images', 'public'); // Save in the 'public/images' directory
        } else {
            return response()->json(['message' => 'Image upload failed.'], 400);
        }

        $task = Task::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image_url' => $imagePath,
            'user_id' => $validatedData['user_id'],
        ]);

        return response()->json(['message' => 'Task created successfully!', 'task' => $task], 201);
    }

    // Get a specific task for a specific user
    public function filter(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
        ]);

        $userId = $validated['user_id'];
        $taskId = $validated['task_id'];

        $task = Task::where('user_id', $userId)
                    ->where('id', $taskId)
                    ->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found for this user'], 404);
        }

        return response()->json(['data' => $task], 200);
    }

    // Update a task
    public function edit(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if a new image is uploaded
        if ($request->hasFile('image_url')) {
            // Delete the old image if it exists
            if ($task->image_url) {
                $imagePath = str_replace('/storage', '', $task->image_url); // Remove public URL prefix
                Storage::disk('public')->delete($imagePath);
            }

            // Store the new image
            $imagePath = $request->file('image_url')->store('images', 'public');
            $task->image_url = $imagePath; // Update image path
        }

        $task->update([
            'title' => $validated['title'] ?? $task->title,
            'description' => $validated['description'] ?? $task->description,
        ]);

        return response()->json(['message' => 'Task updated successfully!', 'task' => $task], 200);
    }

    // Delete a task
    public function delete($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Delete the associated image if it exists
        if ($task->image_url) {
            $imagePath = str_replace('/storage', '', $task->image_url); // Remove public URL prefix
            Storage::disk('public')->delete($imagePath);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully!'], 200);
    }
}

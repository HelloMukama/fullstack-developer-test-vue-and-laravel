<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TaskApiController extends Controller
{
    public function index()
    {
        try {
            // Fetch tasks with their associated projects, users, and tags
            $tasks = Task::with(['project', 'users', 'tags'])->get();
            
            // Check if tasks were successfully retrieved
            if ($tasks->isEmpty()) {
                return response()->json(['message' => 'No tasks found', 'data' => []], 404);
            }

            // Return the tasks with a success message
            return response()->json(['message' => 'Tasks retrieved successfully', 'data' => $tasks], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to retrieve tasks', ['error' => $e->getMessage()]);
            
            // Return a failure response
            return response()->json(['error' => 'Failed to retrieve tasks'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'priority' => 'required|in:High,Low',
            'tags' => 'required|array|min:1|max:2', // Ensure we get 1 or 2 tags
            'tags.*' => 'exists:tags,id', // Ensure each tag exists in the tags table
            'status' => 'required|in:todo,in_progress,done',
            'implementation_date' => 'required|date',
            'users' => 'required|array|min:1|max:3', // Ensure we get 1 to 3 users
            'users.*' => 'exists:users,id', // Ensure each user exists in the users table
        ]);

        try {
            $task = Task::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'project_id' => $validatedData['project_id'],
                'priority' => $validatedData['priority'],
                'status' => $validatedData['status'],
                'implementation_date' => $validatedData['implementation_date'],
            ]);

            // Attach tags to the task
            $task->tags()->attach($validatedData['tags']);

            // Attach users to the task
            $task->users()->attach($validatedData['users']);

            return response()->json(['message' => 'Task created successfully', 'data' => $task], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create task', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::with(['project', 'users', 'tags'])->findOrFail($id);
            return response()->json(['message' => 'Task retrieved successfully', 'data' => $task], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve task', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve task'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string|max:255',
            'project_id' => 'sometimes|required|exists:projects,id',
            'priority' => 'sometimes|required|in:High,Low',
            'tags' => 'sometimes|required|array|min:1|max:2', // Ensure we get 1 or 2 tags if present
            'tags.*' => 'exists:tags,id', // Ensure each tag exists in the tags table
            'status' => 'sometimes|required|in:todo,in_progress,done',
            'implementation_date' => 'sometimes|required|date',
            'users' => 'sometimes|required|array|min:1|max:3', // Ensure we get 1 to 3 users if present
            'users.*' => 'exists:users,id', // Ensure each user exists in the users table
        ]);

        try {
            $task = Task::with(['project', 'users', 'tags'])->findOrFail($id);
            $task->update($validatedData);

            // Update tags for the task if provided
            if (isset($validatedData['tags'])) {
                $task->tags()->sync($validatedData['tags']);
            }

            // Update users for the task if provided
            if (isset($validatedData['users'])) {
                $task->users()->sync($validatedData['users']);
            }

            return response()->json(['message' => 'Task updated successfully', 'data' => $task], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update task', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete task', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
    }

    public function trashed()
    {
        try {
            // Fetch trashed tasks with their associated projects, users, and tags
            $trashedTasks = Task::with(['project', 'users', 'tags'])->onlyTrashed()->get();

            if ($trashedTasks->isEmpty()) {
                return response()->json(['error' => 'No deleted tasks found'], 404);
            }

            return response()->json(['message' => 'This task was deleted recently', 'data' => $trashedTasks], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve deleted tasks', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve deleted tasks'], 500);
        }
    }


    public function restore($id) 
    {
        try {
            $task = Task::withTrashed()->findOrFail($id);
            $task->restore();
            return response()->json(['message' => 'Task restored successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to restore task', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to restore task'], 500);
        }
    }

    public function restoreAll()
    {
        try {
            $trashedTasks = Task::onlyTrashed();
            if ($trashedTasks->count() === 0) {
                return response()->json(['error' => 'No tasks to restore'], 404);
            }
            $trashedTasks->restore();
            return response()->json(['message' => 'All tasks restored successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to restore tasks', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to restore tasks'], 500);
        }
    }

    public function tasksPastImplementationDate()
    {
        try {
            $tasksPastDate = Task::with(['project', 'users', 'tags'])->where('implementation_date', '<', now())->get();
            if ($tasksPastDate->isEmpty()) {
                return response()->json(['error' => 'No tasks past implementation date found'], 404);
            }
            return response()->json(['message' => 'Tasks past implementation date retrieved successfully', 'data' => $tasksPastDate], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tasks past implementation date', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve tasks past implementation date'], 500);
        }
    }
}

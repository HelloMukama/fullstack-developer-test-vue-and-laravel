<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ProjectApiController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::all();
            return response()->json(['message' => 'Projects retrieved successfully', 'data' => $projects], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve projects', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve projects'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $project = Project::create([
                'name' => $validatedData['name'],
            ]);

            return response()->json(['message' => 'Project created successfully', 'data' => $project], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create project', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create project'], 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);
            return response()->json(['message' => 'Project retrieved successfully', 'data' => $project], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve project', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve project'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $project = Project::findOrFail($id);
            $project->update([
                'name' => $validatedData['name'],
            ]);

            return response()->json(['message' => 'Project updated successfully', 'data' => $project], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update project', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update project'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete project', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete project'], 500);
        }
    }
}

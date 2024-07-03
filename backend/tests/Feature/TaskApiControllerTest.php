<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Tag;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class TaskApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_tasks_successfully()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'project',
                        'users',
                        'tags',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    public function test_api_returns_no_tasks_found_message_when_no_tasks_exist()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $response = $this->getJson('/api/v1/tasks/all');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No tasks found',
                'data' => []
            ]);
    }

    public function test_api_creates_task_successfully()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $taskData = Task::factory()->raw();
        $tags = Tag::factory()->count(2)->create()->pluck('id')->toArray();
        $users = User::factory()->count(3)->create()->pluck('id')->toArray();

        $taskData['tags'] = $tags;
        $taskData['users'] = $users;

        $response = $this->postJson('/api/v1/tasks/create', $taskData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task created successfully'
            ]);

        $this->assertDatabaseHas('tasks', ['name' => $taskData['name']]);
    }

    public function test_api_shows_specific_task()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $task = Task::factory()->create();

        $response = $this->getJson("/api/v1/tasks/show/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task retrieved successfully',
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'project' => $task->project->toArray(),
                    'users' => $task->users->toArray(),
                    'tags' => $task->tags->toArray(),
                ]
            ]);
    }

    public function test_api_updates_task_successfully()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $task = Task::factory()->create();
        $updatedData = [
            'name' => 'Updated Task Name',
            'description' => 'Updated description',
            'priority' => 'Low',
            'status' => 'done',
            'implementation_date' => now()->addDays(3)->format('Y-m-d'),
            'tags' => Tag::factory()->count(2)->create()->pluck('id')->toArray(),
            'users' => User::factory()->count(2)->create()->pluck('id')->toArray(),
        ];

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/update", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task updated successfully',
                'data' => [
                    'id' => $task->id,
                    'name' => $updatedData['name'],
                    'priority' => $updatedData['priority'],
                    'status' => $updatedData['status'],
                    'implementation_date' => $updatedData['implementation_date'],
                ]
            ]);

        $this->assertDatabaseHas('tasks', ['name' => $updatedData['name']]);
    }

    public function test_api_deletes_task()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}/delete");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully'
            ]);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_api_restores_deleted_task()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum
        
        $task = Task::factory()->create();
        $task->delete();

        $response = $this->postJson("/api/v1/tasks/trashed/temp/{$task->id}/restore");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task restored successfully.'
            ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'deleted_at' => null]);
    }

    public function test_api_restores_all_deleted_tasks()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum

        Task::factory()->count(3)->create();
        Task::factory()->count(2)->create()->each(function ($task) {
            $task->delete();
        });

        $response = $this->postJson('/api/v1/tasks/trashed/temp/restore-all');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'All tasks restored successfully'
            ]);

        $this->assertDatabaseMissing('tasks', ['deleted_at' => !null]);
    }

    public function test_api_returns_tasks_past_implementation_date()
    {
        $users = User::factory()->count(3)->create();
        Sanctum::actingAs($users->first());  // Authenticate with sanctum

        Task::factory()->create(['implementation_date' => now()->subDays(5)]);
        Task::factory()->create(['implementation_date' => now()->addDays(3)]);

        $response = $this->getJson('/api/v1/tasks/past-implementation');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'project_id',
                        'users',
                        'tags',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
            ->assertJson(['message' => 'Tasks past implementation date retrieved successfully']);
    }



}


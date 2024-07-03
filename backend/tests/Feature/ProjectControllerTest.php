<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_create_project()
    {
        // Create users and authenticate the first user with Sanctum
        $users = User::factory()->count(4)->create();
        Sanctum::actingAs($users->first());

        $payload = [
            'name' => 'Project 002'
        ];

        $response = $this->postJson('/api/v1/projects/create', $payload);

        $response->assertStatus(201);
        $responseData = $response->json('data');
        
        $this->assertEquals('Project created successfully', $response->json('message'));
        $this->assertEquals('Project 002', $responseData['name']);
        $this->assertEquals(1, $responseData['id']);
        $this->assertNotNull($responseData['created_at']);
        $this->assertNotNull($responseData['updated_at']);

        $this->assertDatabaseHas('projects', [
            'name' => 'Project 002'
        ]);
    }

    public function test_api_can_retrieve_all_projects()
    {
        // Create users and authenticate the first user with Sanctum
        $users = User::factory()->count(4)->create();
        Sanctum::actingAs($users->first());

        // Create sample projects with specific names to match the test expectations
        Project::factory()->create([
            'name' => 'Meridian',
            'created_at' => now()->setTime(11, 30, 30)->toJSON(),
            'updated_at' => now()->setTime(11, 30, 30)->toJSON()
        ]);
        Project::factory()->create([
            'name' => 'Risen',
            'created_at' => now()->setTime(11, 30, 30)->toJSON(),
            'updated_at' => now()->setTime(11, 30, 30)->toJSON()
        ]);

        $response = $this->getJson('/api/v1/projects/all');

        $response->assertStatus(200);
        $responseData = $response->json('data');

        $this->assertEquals('Projects retrieved successfully', $response->json('message'));
        
        $this->assertCount(2, $responseData);
        $this->assertEquals('Meridian', $responseData[0]['name']);
        $this->assertEquals('Risen', $responseData[1]['name']);
        $this->assertNotNull($responseData[0]['created_at']);
        $this->assertNotNull($responseData[0]['updated_at']);
        $this->assertNotNull($responseData[1]['created_at']);
        $this->assertNotNull($responseData[1]['updated_at']);
    }
}

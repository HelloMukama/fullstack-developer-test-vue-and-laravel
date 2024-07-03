<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'project_id' => Project::factory(), // Using the Project factory
            'priority' => $this->faker->randomElement(['High', 'Low']),
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'done']),
            'implementation_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            // Attach tags to the task
            $tagIds = \App\Models\Tag::factory(rand(1, 3))->create()->pluck('id')->toArray();
            $task->tags()->sync($tagIds);

            // Attach users to the task
            $userIds = \App\Models\User::factory(rand(1, 5))->create()->pluck('id')->toArray();
            $task->users()->sync($userIds);
        });
    }
}

<?php
namespace Database\Factories;
use App\Enums\TaskStatus; use App\Models\Project; use Illuminate\Database\Eloquent\Factories\Factory;
class TaskFactory extends Factory { public function definition(): array { return ['project_id'=>Project::factory(),'milestone_id'=>null,'assigned_to'=>null,'title'=>fake()->sentence(4),'description'=>fake()->optional()->sentence(),'status'=>TaskStatus::Todo,'priority'=>fake()->numberBetween(1,5),'due_at'=>now()->addWeek(),'completed_at'=>null]; } }

<?php
namespace Database\Factories;
use App\Enums\MilestoneStatus; use App\Models\Project; use Illuminate\Database\Eloquent\Factories\Factory;
class MilestoneFactory extends Factory { public function definition(): array { return ['project_id'=>Project::factory(),'title'=>fake()->sentence(3),'description'=>fake()->optional()->sentence(),'status'=>MilestoneStatus::Pending,'position'=>fake()->unique()->numberBetween(1,100000),'due_on'=>today()->addWeeks(2),'completed_at'=>null]; } }

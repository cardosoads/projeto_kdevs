<?php
namespace Database\Factories;
use App\Enums\FollowUpStatus; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class FollowUpFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'interaction_id'=>null,'title'=>fake()->sentence(4),'notes'=>fake()->optional()->sentence(),'status'=>FollowUpStatus::Scheduled,'scheduled_at'=>now()->addDays(3),'completed_at'=>null]; } }

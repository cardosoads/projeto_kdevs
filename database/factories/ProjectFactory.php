<?php
namespace Database\Factories;
use App\Enums\ProjectStatus; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class ProjectFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'offer_id'=>null,'name'=>fake()->catchPhrase(),'description'=>fake()->sentence(),'status'=>ProjectStatus::Planned,'starts_on'=>today(),'due_on'=>today()->addMonth(),'completed_on'=>null]; } }

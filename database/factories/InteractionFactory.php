<?php
namespace Database\Factories;
use App\Enums\InteractionDirection; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class InteractionFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'contact_point_id'=>null,'message_draft_id'=>null,'type'=>'email','direction'=>InteractionDirection::Outbound,'status'=>'recorded','external_id'=>fake()->unique()->uuid(),'summary'=>fake()->sentence(),'metadata'=>['synthetic'=>true],'occurred_at'=>now()]; } }

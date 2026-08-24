<?php
namespace Database\Factories;
use App\Enums\MessageDraftStatus; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class MessageDraftFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'offer_id'=>null,'contact_point_id'=>null,'channel'=>'email','subject'=>fake()->sentence(5),'body'=>fake()->paragraphs(2,true),'status'=>MessageDraftStatus::Draft,'version'=>1]; } }

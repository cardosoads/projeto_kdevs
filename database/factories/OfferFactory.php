<?php
namespace Database\Factories;
use App\Enums\OfferStatus; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class OfferFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'diagnosis_id'=>null,'title'=>fake()->catchPhrase(),'description'=>fake()->paragraph(),'value_proposition'=>fake()->sentence(),'status'=>OfferStatus::Draft,'price'=>fake()->randomFloat(2,500,20000),'currency'=>'BRL']; } }

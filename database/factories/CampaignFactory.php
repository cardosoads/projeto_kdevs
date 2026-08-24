<?php
namespace Database\Factories;
use App\Enums\CampaignStatus; use Illuminate\Database\Eloquent\Factories\Factory;
class CampaignFactory extends Factory { public function definition(): array { return ['name'=>fake()->catchPhrase(),'description'=>fake()->sentence(),'status'=>CampaignStatus::Draft,'starts_at'=>now(),'ends_at'=>now()->addMonths(2)]; } }

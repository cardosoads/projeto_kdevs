<?php
namespace Database\Factories;
use App\Enums\DiagnosisStatus; use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class DiagnosisFactory extends Factory { public function definition(): array { return ['lead_id'=>Lead::factory(),'status'=>DiagnosisStatus::Completed,'summary'=>fake()->paragraph(),'pain_points'=>[fake()->sentence()],'opportunities'=>[fake()->sentence()],'completed_at'=>now()]; } }

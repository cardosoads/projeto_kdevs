<?php
namespace Database\Factories;
use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class ContactPointFactory extends Factory { public function definition(): array { $email=fake()->unique()->safeEmail(); return ['lead_id'=>Lead::factory(),'type'=>'email','value'=>$email,'normalized_value'=>strtolower($email),'is_primary'=>true,'verified_at'=>null]; } }

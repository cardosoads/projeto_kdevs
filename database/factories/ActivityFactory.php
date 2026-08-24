<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ActivityFactory extends Factory { public function definition(): array { return ['actor_user_id'=>null,'action'=>'created','subject_type'=>null,'subject_id'=>null,'properties'=>['synthetic'=>true],'request_id'=>fake()->uuid(),'correlation_id'=>fake()->uuid(),'ip_address'=>fake()->ipv4(),'user_agent'=>'Synthetic seeder','occurred_at'=>now()]; } }

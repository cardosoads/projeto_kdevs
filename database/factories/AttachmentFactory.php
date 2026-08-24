<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class AttachmentFactory extends Factory { public function definition(): array { $path='synthetic/'.fake()->unique()->uuid().'.pdf'; return ['attachable_type'=>null,'attachable_id'=>null,'disk'=>'local','path'=>$path,'original_name'=>fake()->word().'.pdf','mime_type'=>'application/pdf','size'=>fake()->numberBetween(1000,1000000),'checksum'=>hash('sha256',$path),'metadata'=>['synthetic'=>true]]; } }

<?php
namespace Database\Factories;
use App\Models\Lead; use Illuminate\Database\Eloquent\Factories\Factory;
class SourceEvidenceFactory extends Factory { public function definition(): array { $url=fake()->unique()->url(); return ['lead_id'=>Lead::factory(),'kind'=>'web','url'=>$url,'title'=>fake()->sentence(4),'excerpt'=>fake()->paragraph(),'fingerprint'=>hash('sha256',$url),'metadata'=>['synthetic'=>true],'captured_at'=>now()]; } }

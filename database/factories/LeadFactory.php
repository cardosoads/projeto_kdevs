<?php
namespace Database\Factories;
use App\Enums\LeadStatus; use App\Models\Campaign; use Illuminate\Database\Eloquent\Factories\Factory;
class LeadFactory extends Factory { public function definition(): array { $domain=fake()->unique()->domainName(); return ['campaign_id'=>Campaign::factory(),'name'=>fake()->name(),'company_name'=>fake()->company(),'website_url'=>'https://'.$domain,'normalized_domain'=>strtolower($domain),'status'=>LeadStatus::Discovered,'notes'=>fake()->optional()->sentence()]; } }

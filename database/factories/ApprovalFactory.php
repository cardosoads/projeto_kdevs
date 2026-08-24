<?php
namespace Database\Factories;
use App\Enums\ApprovalStatus; use App\Models\MessageDraft; use Illuminate\Database\Eloquent\Factories\Factory;
class ApprovalFactory extends Factory { public function definition(): array { return ['message_draft_id'=>MessageDraft::factory(),'status'=>ApprovalStatus::Pending,'decided_by'=>null,'notes'=>null,'decided_at'=>null]; } }

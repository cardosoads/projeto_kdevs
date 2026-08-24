<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Approval;
use App\Models\Attachment;
use App\Models\Campaign;
use App\Models\ContactPoint;
use App\Models\Diagnosis;
use App\Models\FollowUp;
use App\Models\Interaction;
use App\Models\Lead;
use App\Models\MessageDraft;
use App\Models\Milestone;
use App\Models\Offer;
use App\Models\Project;
use App\Models\SourceEvidence;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create(['name' => 'Synthetic Operator', 'email' => 'operator@example.test']);
        $campaign = Campaign::factory()->create(['created_by' => $user->id, 'updated_by' => $user->id]);

        Lead::factory()->count(3)->for($campaign)->create()->each(function (Lead $lead) use ($user) {
            $contact = ContactPoint::factory()->for($lead)->create();
            SourceEvidence::factory()->count(2)->for($lead)->create();
            $diagnosis = Diagnosis::factory()->for($lead)->create();
            $offer = Offer::factory()->for($lead)->for($diagnosis)->create();
            $draft = MessageDraft::factory()->for($lead)->for($offer)->for($contact)->create();
            Approval::factory()->for($draft)->create();
            $interaction = Interaction::factory()->for($lead)->for($contact)->for($draft)->create();
            FollowUp::factory()->for($lead)->for($interaction)->create();
            $project = Project::factory()->for($lead)->for($offer)->create();
            $milestone = Milestone::factory()->for($project)->create(['position' => 1]);
            Task::factory()->for($project)->for($milestone)->create(['assigned_to' => $user->id]);
            Attachment::factory()->for($lead, 'attachable')->create();
            Activity::factory()->for($user, 'actor')->for($lead, 'subject')->create(['action' => 'lead.seeded']);
        });
    }
}

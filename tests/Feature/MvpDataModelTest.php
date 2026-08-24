<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Enums\LeadStatus;
use App\Enums\MessageDraftStatus;
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
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class MvpDataModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_domain_graph_can_be_persisted_and_navigated(): void
    {
        $campaign = Campaign::factory()->create(['status' => CampaignStatus::Active]);
        $lead = Lead::factory()->for($campaign)->create(['status' => LeadStatus::Qualified]);
        $contact = ContactPoint::factory()->for($lead)->create();
        $evidence = SourceEvidence::factory()->for($lead)->create();
        $diagnosis = Diagnosis::factory()->for($lead)->create();
        $offer = Offer::factory()->for($lead)->for($diagnosis)->create();
        $draft = MessageDraft::factory()->for($lead)->for($offer)->for($contact)->create(['status' => MessageDraftStatus::PendingApproval]);
        $approval = Approval::factory()->for($draft)->create();
        $interaction = Interaction::factory()->for($lead)->for($contact)->for($draft)->create();
        $followUp = FollowUp::factory()->for($lead)->for($interaction)->create();
        $project = Project::factory()->for($lead)->for($offer)->create();
        $milestone = Milestone::factory()->for($project)->create(['position' => 1]);
        $task = Task::factory()->for($project)->for($milestone)->create();
        $attachment = Attachment::factory()->for($lead, 'attachable')->create();
        $activity = Activity::factory()->for($lead, 'subject')->create(['action' => 'lead.qualified']);

        $this->assertSame(CampaignStatus::Active, $campaign->status);
        $this->assertSame(LeadStatus::Qualified, $lead->status);
        $this->assertTrue($campaign->leads->contains($lead));
        $this->assertTrue($lead->contactPoints->contains($contact));
        $this->assertTrue($lead->sourceEvidence->contains($evidence));
        $this->assertTrue($lead->diagnosis->is($diagnosis));
        $this->assertTrue($draft->approvals->contains($approval));
        $this->assertTrue($interaction->followUps->contains($followUp));
        $this->assertTrue($project->milestones->contains($milestone));
        $this->assertTrue($milestone->tasks->contains($task));
        $this->assertTrue($lead->attachments->contains($attachment));
        $this->assertTrue($activity->subject->is($lead));
    }

    public function test_lead_contact_evidence_and_interaction_deduplication_is_enforced(): void
    {
        $lead = Lead::factory()->create(['normalized_domain' => 'example.test']);

        $this->expectException(QueryException::class);
        Lead::factory()->for($lead->campaign)->create(['normalized_domain' => 'example.test']);
    }

    public function test_contact_point_deduplication_is_enforced(): void
    {
        $contact = ContactPoint::factory()->create(['type' => 'email', 'normalized_value' => 'person@example.test']);

        $this->expectException(QueryException::class);
        ContactPoint::factory()->for($contact->lead)->create(['type' => 'email', 'normalized_value' => 'person@example.test']);
    }

    public function test_source_evidence_deduplication_is_enforced(): void
    {
        $evidence = SourceEvidence::factory()->create(['fingerprint' => str_repeat('a', 64)]);

        $this->expectException(QueryException::class);
        SourceEvidence::factory()->for($evidence->lead)->create(['fingerprint' => str_repeat('a', 64)]);
    }

    public function test_interaction_external_id_deduplication_is_enforced(): void
    {
        $interaction = Interaction::factory()->create(['external_id' => 'provider-event-1']);

        $this->expectException(QueryException::class);
        Interaction::factory()->for($interaction->lead)->create(['external_id' => 'provider-event-1']);
    }

    public function test_activity_is_append_only_and_supports_anonymous_actor(): void
    {
        $activity = Activity::factory()->create(['actor_user_id' => null, 'properties' => ['source' => 'system']]);

        $this->assertNull($activity->actor);
        $this->assertSame(['source' => 'system'], $activity->properties);

        try {
            $activity->update(['action' => 'changed']);
            $this->fail('Updating an activity should fail.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString('append-only', $exception->getMessage());
        }

        $this->expectException(LogicException::class);
        $activity->delete();
    }
}

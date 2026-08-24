<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('website_url')->nullable();
            $table->string('normalized_domain')->nullable();
            $table->string('status')->default('discovered')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['campaign_id', 'normalized_domain']);
            $table->index(['campaign_id', 'status']);
        });

        Schema::create('contact_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('value');
            $table->string('normalized_value');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->unique(['lead_id', 'type', 'normalized_value']);
            $table->index(['lead_id', 'is_primary']);
        });

        Schema::create('source_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('kind')->default('web');
            $table->text('url');
            $table->string('title')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('fingerprint', 64);
            $table->json('metadata')->nullable();
            $table->timestamp('captured_at');
            $table->timestamps();
            $table->unique(['lead_id', 'fingerprint']);
            $table->index(['lead_id', 'captured_at']);
        });

        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft')->index();
            $table->text('summary')->nullable();
            $table->json('pain_points')->nullable();
            $table->json('opportunities')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique('lead_id');
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diagnosis_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('value_proposition')->nullable();
            $table->string('status')->default('draft')->index();
            $table->decimal('price', 12, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->timestamps();
            $table->index(['lead_id', 'status']);
        });

        Schema::create('message_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_point_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('status')->default('draft')->index();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->index(['lead_id', 'channel', 'status']);
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_draft_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->index(['message_draft_id', 'created_at']);
        });

        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_point_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('message_draft_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('direction');
            $table->string('status')->nullable()->index();
            $table->string('external_id')->nullable();
            $table->text('summary')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->unique(['lead_id', 'external_id']);
            $table->index(['lead_id', 'occurred_at']);
        });

        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled')->index();
            $table->timestamp('scheduled_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['lead_id', 'scheduled_at']);
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('planned')->index();
            $table->date('starts_on')->nullable();
            $table->date('due_on')->nullable();
            $table->date('completed_on')->nullable();
            $table->timestamps();
            $table->index(['lead_id', 'status']);
        });

        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('position')->default(0);
            $table->date('due_on')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['project_id', 'position']);
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('milestone_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('todo')->index();
            $table->unsignedTinyInteger('priority')->default(3);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'status', 'due_at']);
            $table->index(['milestone_id', 'status']);
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('attachable');
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['disk', 'path']);
            $table->index('checksum');
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->index();
            $table->nullableMorphs('subject');
            $table->json('properties')->nullable();
            $table->uuid('request_id')->nullable()->index();
            $table->uuid('correlation_id')->nullable()->index();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['subject_type', 'subject_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('interactions');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('message_drafts');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('source_evidence');
        Schema::dropIfExists('contact_points');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('campaigns');
    }
};

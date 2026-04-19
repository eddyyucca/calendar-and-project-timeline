<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('planning');
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->text('goal')->nullable();
            $table->timestamps();
        });

        Schema::create('sprints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('planned');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('goal')->nullable();
            $table->timestamps();
        });

        Schema::create('project_tasks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sprint_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->default('story');
            $table->string('priority')->default('medium');
            $table->string('status')->default('backlog');
            $table->unsignedTinyInteger('story_points')->default(1);
            $table->text('description')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('sprints');
        Schema::dropIfExists('projects');
    }
};

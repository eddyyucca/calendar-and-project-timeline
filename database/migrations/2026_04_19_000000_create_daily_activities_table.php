<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('activity_date');
            $table->string('category')->default('Operasional');
            $table->string('priority')->default('Normal');
            $table->string('status')->default('Belum Mulai');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->text('description')->nullable();
            $table->text('blocker')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};

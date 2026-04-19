<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('employee')->after('email');
        });

        Schema::table('activity_comments', function (Blueprint $table): void {
            $table->unsignedTinyInteger('progress')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('activity_comments', function (Blueprint $table): void {
            $table->dropColumn('progress');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
        });
    }
};

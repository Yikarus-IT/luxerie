<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_contents', function (Blueprint $table) {
            $table->json('scheduled_content')->nullable();
            $table->json('scheduled_media')->nullable();
            $table->json('scheduled_layout')->nullable();
            $table->timestamp('scheduled_for')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('homepage_contents', fn (Blueprint $table) => $table->dropColumn(['scheduled_content', 'scheduled_media', 'scheduled_layout', 'scheduled_for']));
    }
};

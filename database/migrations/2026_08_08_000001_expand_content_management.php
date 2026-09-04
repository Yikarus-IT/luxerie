<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_contents', function (Blueprint $table) {
            $table->json('published_content')->nullable()->after('content');
            $table->json('published_media')->nullable()->after('media');
            $table->json('layout')->nullable()->after('published_media');
            $table->json('published_layout')->nullable()->after('layout');
            $table->timestamp('published_at')->nullable();
        });

        DB::table('homepage_contents')->update([
            'published_content' => DB::raw('content'),
            'published_media' => DB::raw('media'),
        ]);

        Schema::create('homepage_revisions', function (Blueprint $table) {
            $table->id();
            $table->json('content');
            $table->json('media')->nullable();
            $table->json('layout')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->json('values');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('content_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 300)->nullable();
            $table->longText('body');
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('quote');
            $table->foreignId('media_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('description');
            $table->json('properties')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('content_pages');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('homepage_revisions');
        Schema::table('homepage_contents', function (Blueprint $table) {
            $table->dropColumn(['published_content', 'published_media', 'layout', 'published_layout', 'published_at']);
        });
    }
};

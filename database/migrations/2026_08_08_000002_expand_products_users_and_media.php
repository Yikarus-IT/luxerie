<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('administrator')->after('is_admin');
        });
        DB::table('users')->where('is_admin', true)->update(['role' => 'administrator']);

        Schema::table('media_assets', function (Blueprint $table) {
            $table->unsignedTinyInteger('focal_x')->default(50);
            $table->unsignedTinyInteger('focal_y')->default(50);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('primary_media_id')->nullable()->after('image_url')->constrained('media_assets')->nullOnDelete();
            $table->json('gallery_media_ids')->nullable()->after('primary_media_id');
            $table->text('ingredients')->nullable();
            $table->text('directions')->nullable();
            $table->text('precautions')->nullable();
            $table->string('barcode', 80)->nullable();
            $table->decimal('weight_grams', 10, 2)->nullable();
            $table->decimal('package_length_cm', 8, 2)->nullable();
            $table->decimal('package_width_cm', 8, 2)->nullable();
            $table->decimal('package_height_cm', 8, 2)->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->string('amazon_asin', 30)->nullable();
            $table->string('amazon_url')->nullable();
            $table->string('amazon_status', 30)->default('not_connected');
            $table->string('amazon_title')->nullable();
            $table->text('amazon_description')->nullable();
            $table->string('mercadolibre_item_id', 60)->nullable();
            $table->string('mercadolibre_url')->nullable();
            $table->string('mercadolibre_status', 30)->default('not_connected');
            $table->string('mercadolibre_title')->nullable();
            $table->text('mercadolibre_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('primary_media_id');
            $table->dropColumn(['gallery_media_ids', 'ingredients', 'directions', 'precautions', 'barcode', 'weight_grams', 'package_length_cm', 'package_width_cm', 'package_height_cm', 'seo_title', 'seo_description', 'amazon_asin', 'amazon_url', 'amazon_status', 'amazon_title', 'amazon_description', 'mercadolibre_item_id', 'mercadolibre_url', 'mercadolibre_status', 'mercadolibre_title', 'mercadolibre_description']);
        });
        Schema::table('media_assets', fn (Blueprint $table) => $table->dropColumn(['focal_x', 'focal_y']));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};

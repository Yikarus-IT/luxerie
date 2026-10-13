<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table): void {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained()->nullOnDelete()->index();
        });

        Schema::create('customer_addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('label', 80);
            $table->string('recipient_name', 160);
            $table->string('phone', 40)->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('neighborhood', 160)->nullable();
            $table->string('city', 120);
            $table->string('state', 120);
            $table->string('postal_code', 12);
            $table->string('country', 2)->default('MX');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
        Schema::table('carts', function (Blueprint $table): void {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};

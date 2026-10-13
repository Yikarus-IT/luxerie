<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->decimal('bulk_price', 10, 2)->nullable()->after('price');
            $table->unsignedInteger('bulk_min_quantity')->nullable()->after('bulk_price');
        });
        DB::table('products')->where('sku', 'LUX-CLARA-050')->update(['price' => 420, 'bulk_price' => 360, 'bulk_min_quantity' => 5]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['bulk_price', 'bulk_min_quantity']);
        });
    }
};

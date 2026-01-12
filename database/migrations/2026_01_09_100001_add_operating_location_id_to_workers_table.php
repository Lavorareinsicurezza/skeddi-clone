<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->foreignId('operating_location_id')->nullable()->after('company_id')->constrained('operating_locations')->onDelete('set null');
            
            // Add index for performance
            $table->index(['company_id', 'operating_location_id']);
            $table->index('operating_location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropForeign(['operating_location_id']);
            $table->dropColumn('operating_location_id');
        });
    }
};
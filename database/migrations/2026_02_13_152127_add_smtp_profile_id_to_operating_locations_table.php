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
        Schema::table('operating_locations', function (Blueprint $table) {
            $table->foreignId('smtp_profile_id')->nullable()->after('company_id')->constrained('smtp_profiles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operating_locations', function (Blueprint $table) {
            $table->dropForeign(['smtp_profile_id']);
            $table->dropColumn('smtp_profile_id');
        });
    }
};

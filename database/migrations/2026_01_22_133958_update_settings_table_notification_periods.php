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
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'days_prior_course_deadline',
                'days_prior_health_insurance',
                'days_prior_maintenance_deadline'
            ]);
            $table->json('notification_periods')->default(json_encode([90, 30]))->nullable()->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('notification_periods');
            $table->integer('days_prior_course_deadline')->default(30);
            $table->integer('days_prior_health_insurance')->default(30);
            $table->integer('days_prior_maintenance_deadline')->default(30);
        });
    }
};

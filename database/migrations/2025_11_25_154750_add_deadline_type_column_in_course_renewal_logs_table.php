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
        Schema::table('course_renewal_logs', function (Blueprint $table) {
            $table->string('deadline_type')->after('company_course_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_renewal_logs', function (Blueprint $table) {
            $table->dropColumn('deadline_type');
        });
    }
};

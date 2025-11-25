<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_renewal_logs', function (Blueprint $table) {

            // Rename training_plan_record_id to item_id if exists
            if (Schema::hasColumn('course_renewal_logs', 'training_plan_record_id')) {
                $table->renameColumn('training_plan_record_id', 'item_id');
            }

            // Drop foreign key on item_id (generic column now)
            if (Schema::hasColumn('course_renewal_logs', 'item_id')) {
                $table->dropForeign(['item_id']);
                $table->unsignedBigInteger('item_id')->change(); // still store the ID
            }

            // Make worker_id nullable safely
            if (Schema::hasColumn('course_renewal_logs', 'worker_id')) {
                $table->dropForeign(['worker_id']);
                $table->unsignedBigInteger('worker_id')->nullable()->change();
                $table->foreign('worker_id')->references('id')->on('workers')->onDelete('set null');
            }

            // company_course_type_id nullable
            if (Schema::hasColumn('course_renewal_logs', 'company_course_type_id')) {
                $table->dropForeign(['company_course_type_id']);
                $table->unsignedBigInteger('company_course_type_id')->nullable()->change();
                $table->foreign('company_course_type_id')->references('id')->on('company_course_types')->onDelete('set null');
            }

            // Add deadline_type only if not exists
            if (!Schema::hasColumn('course_renewal_logs', 'deadline_type')) {
                $table->string('deadline_type')->after('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_renewal_logs', function (Blueprint $table) {

            // Rollback rename
            if (Schema::hasColumn('course_renewal_logs', 'item_id')) {
                $table->renameColumn('item_id', 'training_plan_record_id');
                $table->dropForeign(['training_plan_record_id']); // drop FK if exists
            }

            // Revert worker_id
            if (Schema::hasColumn('course_renewal_logs', 'worker_id')) {
                $table->dropForeign(['worker_id']);
                $table->unsignedBigInteger('worker_id')->nullable(false)->change();
                $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            }

            // Revert company_course_type_id
            if (Schema::hasColumn('course_renewal_logs', 'company_course_type_id')) {
                $table->dropForeign(['company_course_type_id']);
                $table->unsignedBigInteger('company_course_type_id')->nullable(false)->change();
                $table->foreign('company_course_type_id')->references('id')->on('company_course_types')->onDelete('restrict');
            }

            // Remove deadline_type column if added
            if (Schema::hasColumn('course_renewal_logs', 'deadline_type')) {
                $table->dropColumn('deadline_type');
            }
        });
    }
};

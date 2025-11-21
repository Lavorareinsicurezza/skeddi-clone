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
        Schema::create('course_renewal_logs', function (Blueprint $table) {
            $table->id();
           // Foreign Keys - Clean & Consistent with your style
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->onDelete('cascade');

            $table->foreignId('training_plan_record_id')
                  ->constrained('training_plan_records')
                  ->onDelete('cascade');

            $table->foreignId('worker_id')
                  ->constrained('workers')
                  ->onDelete('cascade');

            $table->foreignId('company_course_type_id')  // Replaced course_name string
                  ->constrained('company_course_types')
                  ->onDelete('restrict'); // prevent deletion if logs exist

            $table->foreignId('renewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Managed By (person responsible - can be a user or worker name)
            $table->string('managed_by'); // e.g. "A CURA DI: John Doe"

            // Subject / Object of the course
            $table->string('subject');

            // Dates - all proper date type (not string!)
            $table->date('previous_expiry_date');
            $table->date('course_update_date');         // renewal_date from request
            $table->date('new_expiry_date');
            $table->date('renewal_operation_date');     // when the renewal was logged (usually today)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_renewal_logs');
    }
};

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
        Schema::create('training_plan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('company_course_type_id')->constrained('company_course_types')->onDelete('cascade');
            $table->date('training_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('to_be_scheduled')->default(false);
            $table->timestamps();

            // Unique constraint to prevent duplicate records
            $table->unique(['worker_id', 'company_course_type_id'], 'worker_course_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_plan_records');
    }
};

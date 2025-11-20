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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('first_name');
            $table->string('surname');
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->tinyInteger('workplace_safety_risk')->default(0); // 0 = unchecked, 1 = checked
            $table->text('workplace_safety_risk_note')->nullable();
            $table->string('workplace_safety_risk_document')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('additional_information')->nullable();
            $table->text('worker_documentation')->nullable();
            $table->text('ppe')->nullable(); // Personal Protective Equipment
            $table->text('movement_history')->nullable();
            $table->text('training_experience')->nullable();
            $table->text('medical_visits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};

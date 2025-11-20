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
        Schema::create('company_course_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('course_type_id')->constrained('course_types')->onDelete('cascade');
            $table->string('name');
            $table->integer('validity_years')->nullable();
            $table->string('generic_column_name')->nullable();
            $table->string('expiration_column_name')->nullable();
            $table->boolean('is_generic')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_course_types');
    }
};

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
        Schema::create('operating_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('address_street')->nullable();
            $table->string('address_number', 20)->nullable();
            $table->string('address_postal', 20)->nullable();
            $table->string('address_city', 100)->nullable();
            $table->string('site_contact_name')->nullable();
            $table->string('site_contact_phone', 20)->nullable();
            $table->string('site_contact_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index('company_id');
            $table->index(['company_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_locations');
    }
};

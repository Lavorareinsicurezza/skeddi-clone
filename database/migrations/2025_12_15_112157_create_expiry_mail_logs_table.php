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
        Schema::create('expiry_mail_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            // training_plan | course | document | visit

            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('mail_type');
            // one_month | one_week | last_day

            $table->timestamp('sent_at');
            $table->timestamps();

            $table->unique(['module', 'record_id', 'mail_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiry_mail_logs');
    }
};

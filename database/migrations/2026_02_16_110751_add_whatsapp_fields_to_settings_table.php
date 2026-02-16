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
            // WhatsApp Business API Configuration
            $table->string('whatsapp_api_url')->nullable()->after('whatsapp_smtp_reply_to');
            $table->text('whatsapp_api_key')->nullable()->after('whatsapp_api_url');
            $table->string('whatsapp_phone_number_id')->nullable()->after('whatsapp_api_key');
            $table->string('whatsapp_business_account_id')->nullable()->after('whatsapp_phone_number_id');
            $table->string('whatsapp_template_name')->default('expiry_reminder')->after('whatsapp_business_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_api_url',
                'whatsapp_api_key',
                'whatsapp_phone_number_id',
                'whatsapp_business_account_id',
                'whatsapp_template_name',
            ]);
        });
    }
};

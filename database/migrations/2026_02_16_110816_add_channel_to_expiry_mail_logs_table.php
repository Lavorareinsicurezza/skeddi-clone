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
        Schema::table('expiry_mail_logs', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique(['module', 'record_id', 'mail_type']);

            // Add channel column (email or whatsapp)
            $table->enum('channel', ['email', 'whatsapp'])->default('email')->after('mail_type');

            // Add new unique constraint including channel
            $table->unique(['module', 'record_id', 'mail_type', 'channel'], 'expiry_logs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expiry_mail_logs', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('expiry_logs_unique');

            // Drop channel column
            $table->dropColumn('channel');

            // Restore old unique constraint
            $table->unique(['module', 'record_id', 'mail_type']);
        });
    }
};

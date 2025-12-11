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
        Schema::table('workers', function (Blueprint $table) {
            $table->text('workplace_safety_risk_note')->nullable()->after('workplace_safety_risk');
            $table->string('workplace_safety_risk_document')->nullable()->after('workplace_safety_risk_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(['workplace_safety_risk_note', 'workplace_safety_risk_document']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('operating_locations', function (Blueprint $table) {
            $table->string('address')->nullable()->after('name');
        });

        // Backfill new 'address' from existing parts
        // Uses CONCAT_WS to safely join non-null parts with ", "
        DB::statement("
            UPDATE operating_locations
            SET address = CONCAT_WS(', ',
                NULLIF(TRIM(address_street), ''),
                NULLIF(TRIM(address_number), ''),
                NULLIF(TRIM(address_city), ''),
                NULLIF(TRIM(address_postal), '')
            )
        ");

        Schema::table('operating_locations', function (Blueprint $table) {
            $table->dropColumn(['address_street', 'address_number', 'address_postal', 'address_city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operating_locations', function (Blueprint $table) {
            $table->string('address_street')->nullable()->after('name');
            $table->string('address_number', 20)->nullable()->after('address_street');
            $table->string('address_postal', 20)->nullable()->after('address_number');
            $table->string('address_city', 100)->nullable()->after('address_postal');
        });

        // Best-effort restore: put full address into street, others null
        DB::statement("
            UPDATE operating_locations
            SET address_street = address,
                address_number = NULL,
                address_postal = NULL,
                address_city = NULL
        ");

        Schema::table('operating_locations', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};

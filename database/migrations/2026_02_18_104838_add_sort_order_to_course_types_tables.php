<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add sort_order to master course_types
        Schema::table('course_types', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('company_id');
        });

        // Add sort_order to company_course_types
        Schema::table('company_course_types', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('company_id');
        });

        // Populate sort_order for existing course_types (ordered by id per company)
        $companyIds = DB::table('course_types')->distinct()->pluck('company_id');
        foreach ($companyIds as $companyId) {
            $records = DB::table('course_types')
                ->where('company_id', $companyId)
                ->orderBy('id')
                ->pluck('id');

            foreach ($records as $index => $id) {
                DB::table('course_types')
                    ->where('id', $id)
                    ->update(['sort_order' => $index + 1]);
            }
        }

        // Propagate sort_order from course_types to company_course_types
        DB::statement('
            UPDATE company_course_types cct
            JOIN course_types ct ON ct.id = cct.course_type_id
            SET cct.sort_order = ct.sort_order
        ');
    }

    public function down(): void
    {
        Schema::table('course_types', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('company_course_types', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};

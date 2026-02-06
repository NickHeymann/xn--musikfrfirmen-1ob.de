<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('calendar_bookings', function (Blueprint $table) {
            $table->string('company')->after('name');
            $table->json('company_research')->nullable()->after('company');
        });

        // Fill existing null company values before making non-nullable
        DB::table('contact_submissions')
            ->whereNull('company')
            ->update(['company' => '']);

        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('company')->nullable(false)->change();
            $table->json('company_research')->nullable()->after('company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_bookings', function (Blueprint $table) {
            $table->dropColumn(['company', 'company_research']);
        });

        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('company')->nullable()->change();
            $table->dropColumn('company_research');
        });
    }
};

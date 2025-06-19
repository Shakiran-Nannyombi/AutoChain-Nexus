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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(false);
            $table->boolean('inventory_alerts')->default(false);
            $table->boolean('production_updates')->default(false);
            $table->boolean('vendor_communications')->default(false);
            $table->boolean('report_generation')->default(false);
            $table->boolean('two_factor_authentication')->default(false);
            $table->string('time_zone')->nullable()->default('UTC');
            $table->string('language')->nullable()->default('English (US)');
            $table->string('date_format')->nullable()->default('MM/DD/YYYY');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('auto_refresh_dashboard')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'inventory_alerts',
                'production_updates',
                'vendor_communications',
                'report_generation',
                'two_factor_authentication',
                'time_zone',
                'language',
                'date_format',
                'dark_mode',
                'auto_refresh_dashboard',
            ]);
        });
    }
};

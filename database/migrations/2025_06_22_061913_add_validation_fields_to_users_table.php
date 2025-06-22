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
            if (!Schema::hasColumn('users', 'financial_score')) {
                $table->integer('financial_score')->nullable();
            }
            if (!Schema::hasColumn('users', 'reputation_score')) {
                $table->integer('reputation_score')->nullable();
            }
            if (!Schema::hasColumn('users', 'compliance_score')) {
                $table->integer('compliance_score')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_score')) {
                $table->integer('profile_score')->nullable();
            }
            if (!Schema::hasColumn('users', 'extracted_data')) {
                $table->json('extracted_data')->nullable();
            }
            if (!Schema::hasColumn('users', 'validated_at')) {
                $table->timestamp('validated_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'auto_visit_scheduled')) {
                $table->boolean('auto_visit_scheduled')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'financial_score')) {
                $table->dropColumn('financial_score');
            }
            if (Schema::hasColumn('users', 'reputation_score')) {
                $table->dropColumn('reputation_score');
            }
            if (Schema::hasColumn('users', 'compliance_score')) {
                $table->dropColumn('compliance_score');
            }
            if (Schema::hasColumn('users', 'profile_score')) {
                $table->dropColumn('profile_score');
            }
            if (Schema::hasColumn('users', 'extracted_data')) {
                $table->dropColumn('extracted_data');
            }
            if (Schema::hasColumn('users', 'validated_at')) {
                $table->dropColumn('validated_at');
            }
            if (Schema::hasColumn('users', 'auto_visit_scheduled')) {
                $table->dropColumn('auto_visit_scheduled');
            }
        });
    }
};

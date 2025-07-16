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
            $table->integer('validation_score')->nullable();
            $table->integer('financial_score')->nullable();
            $table->integer('reputation_score')->nullable();
            $table->integer('compliance_score')->nullable();
            $table->integer('profile_score')->nullable();
            $table->json('extracted_data')->nullable();
            $table->dateTime('validated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'validation_score',
                'financial_score',
                'reputation_score',
                'compliance_score',
                'profile_score',
                'extracted_data',
                'validated_at',
            ]);
        });
    }
};

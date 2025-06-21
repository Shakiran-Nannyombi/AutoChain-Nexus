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
        Schema::create('analysts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('company');
            $table->text('address');
            $table->string('profile_picture')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->string('analyst_certification')->nullable();
            $table->text('specialization_areas')->nullable();
            $table->text('research_methodologies')->nullable();
            $table->string('reporting_capabilities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysts');
    }
};

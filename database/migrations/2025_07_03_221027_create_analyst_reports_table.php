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
        Schema::create('analyst_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // e.g., sales, inventory, performance
            $table->string('target_role'); // supplier, retailer, etc.
            $table->text('summary');
            $table->string('report_file')->nullable(); // path to file if downloadable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyst_reports');
    }
};

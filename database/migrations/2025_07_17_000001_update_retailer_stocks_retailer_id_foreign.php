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
        Schema::table('retailer_stocks', function (Blueprint $table) {
            // Drop the old foreign key
            $table->dropForeign(['retailer_id']);
            // Add the new foreign key
            $table->foreign('retailer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retailer_stocks', function (Blueprint $table) {
            $table->dropForeign(['retailer_id']);
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
        });
    }
}; 
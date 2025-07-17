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
        Schema::table('retailer_orders', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['retailer_id']);
            
            // Add the correct foreign key constraint to users table
            $table->foreign('retailer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retailer_orders', function (Blueprint $table) {
            // Drop the users foreign key constraint
            $table->dropForeign(['retailer_id']);
            
            // Restore the original retailers foreign key constraint
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
        });
    }
};

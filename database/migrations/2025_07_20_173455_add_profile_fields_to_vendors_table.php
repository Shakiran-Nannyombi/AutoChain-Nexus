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
        Schema::table('vendors', function (Blueprint $table) {
        $table->string('email')->after('name');
        $table->string('phone')->nullable();
        $table->string('password');
        $table->string('company')->nullable();
        $table->string('address')->nullable();
        $table->string('profile_picture')->nullable();
        $table->json('supporting_documents')->nullable();
        $table->string('vendor_license')->nullable();
        $table->string('product_categories')->nullable();
        $table->string('service_areas')->nullable();
        $table->text('contract_terms')->nullable();
        $table->string('segment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
            'email',
            'phone',
            'password',
            'company',
            'address',
            'profile_picture',
            'supporting_documents',
            'vendor_license',
            'product_categories',
            'service_areas',
            'contract_terms',
            'segment',
            ]);
        });
    }
};

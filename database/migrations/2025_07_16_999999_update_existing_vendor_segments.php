<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('vendors')->whereNull('segment')->update(['segment' => 'Unsegmented']);
    }

    public function down(): void
    {
        // Optionally revert to null, but not strictly necessary
        // DB::table('vendors')->where('segment', 'Unsegmented')->update(['segment' => null]);
    }
}; 
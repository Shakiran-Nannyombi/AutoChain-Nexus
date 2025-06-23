<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('process_flows', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->enum('current_stage', ['raw_materials', 'manufacturing', 'quality_control', 'distribution', 'retail']);
            $table->enum('status', ['in_progress', 'completed', 'failed']);
            $table->timestamp('entered_stage_at')->nullable();
            $table->timestamp('completed_stage_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('process_flows');
    }
}; 
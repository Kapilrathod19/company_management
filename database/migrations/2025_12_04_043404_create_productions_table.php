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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('sr_no');
            $table->date('date');
            $table->string('employee_name');
            $table->string('unit_no');
            $table->string('component_no');
            $table->text('description')->nullable();
            $table->string('process');
            $table->integer('qty');
            $table->float('weight', 8, 2);
            $table->float('total_weight', 10, 2);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};

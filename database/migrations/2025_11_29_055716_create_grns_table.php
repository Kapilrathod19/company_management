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
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('grn_no');
            $table->date('grn_date');
            $table->string('category');
            $table->unsignedBigInteger('party_name');
            $table->string('po_no');
            $table->string('party_challan_no');
            $table->date('party_challan_date');
            $table->string('unit_no');
            $table->unsignedBigInteger('part_no');
            $table->text('description');
            $table->decimal('qty', 15, 2);
            $table->decimal('weight', 15, 2);
            $table->decimal('total_weight', 15, 2);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grns');
    }
};

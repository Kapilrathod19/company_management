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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('supplier_name');
            $table->string('po_no');
            $table->date('po_date')->nullable();
            $table->string('customer_name');
            $table->string('sales_po_no');
            $table->string('unit_no')->nullable();
            $table->string('part_no');
            $table->text('description');
            $table->integer('qty');
            $table->decimal('weight', 10, 2);
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
        Schema::dropIfExists('suppliers');
    }
};

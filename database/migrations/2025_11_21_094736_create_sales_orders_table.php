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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('customer_name');
            $table->string('po_no');
            $table->date('po_date');
            $table->string('part_no');
            $table->text('description')->nullable();
            $table->string('unit');
            $table->integer('qty');
            $table->decimal('weight', 10, 2);
            $table->decimal('total_weight', 10, 2);
            $table->date('delivery_date');
            $table->string('drawing_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};

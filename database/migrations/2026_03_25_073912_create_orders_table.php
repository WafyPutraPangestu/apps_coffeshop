<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->integer('total_price');
            $table->enum('payment_status', ['Unpaid', 'Paid', 'Failed', 'Expired'])->default('Unpaid');
            $table->enum('order_status', ['Pending', 'Processing', 'Completed'])->default('Pending');
            $table->string('payment_method')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

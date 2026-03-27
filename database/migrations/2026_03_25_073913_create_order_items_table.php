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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');


            $table->enum('temperature', ['Hot', 'Ice'])->nullable();
            $table->enum('ice_level', ['Normal', 'Less Ice', 'No Ice'])->nullable();
            $table->enum('sugar_level', ['Normal', 'Less Sugar', 'No Sugar'])->nullable();

            $table->integer('quantity');
            $table->integer('price');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

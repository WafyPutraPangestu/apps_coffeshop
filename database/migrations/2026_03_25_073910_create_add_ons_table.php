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
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Extra Shot", "Oat Milk", "Ice Cream"
            $table->integer('price'); // Harga tambahan, misal: 5000
            $table->boolean('is_available')->default(true); // Bisa dimatikan jika stok habis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};

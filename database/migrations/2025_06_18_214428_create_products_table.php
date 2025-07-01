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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // unsignedBigInteger por defecto
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('sku')->unique();
            $table->string('modelo')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->decimal('precio_venta', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // 👈 asegura que sea unsignedBigInteger
            $table->string('nombre');  // aqui va el nombre de  campo
            $table->timestamps();  //  aqui va el tiempo de regitro al  a la base de datos como el create datetime
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

<?php

use Filament\Tables\View\TablesRenderHook;
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
        Schema::create('seizures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('products_id')->constrained()->cascadeOnDelete();   // llave foreana de la relacion con producto
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete(); // llave foreana de la relacion del campo cliente 
            $table->string('sucursal');
            $table->string('recibido_por');
            $table->text('accesorio')->nullable();
            $table->text('obs_product')->nullable();
            $table->integer('factura');
            $table->date('fecha_factura');
            $table->decimal('cost_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('monto_facturado', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->date('fecha_decomiso');
            $table->decimal('sale_quota', 10, 2)->nullable();
            $table->text('observation_pm')->nullable();
            $table->decimal('monto_cancelado', 10, 2)->nullable();
            $table->decimal('saldo', 10, 2)->nullable();
              $table->date('fecha_entrega');
              $table->string('area')->nullable();
              $table->string('status_producto')->nullable();
              $table->enum('status_producto', ['Regular ', 'Bueno', 'Malo'])->default('Regular');
            $table->enum('status', ['Disponible ', 'Reventa', 'Baja', 'Respuesto', 'Activo Fijo '])->default('Disponible');
            $table->decimal('suggested_price', 10, 2)->nullable();
            $table->text('obs_almacen')->nullable();
            $table->string('attachment')->nullable();
            $table->decimal('suggested_price_gerencia', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seizures');
    }
};

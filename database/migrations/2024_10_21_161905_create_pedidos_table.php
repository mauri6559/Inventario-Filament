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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tienda')->constrained('tiendas','id')->onDelete('cascade');
            $table->foreignId('id_proveedor')->constrained('proveedors','id')->onDelete('cascade');
            $table->decimal('total', 10, 2);  // Precio total de la venta
            $table->date('fecha')->default(now()->toDateString()); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

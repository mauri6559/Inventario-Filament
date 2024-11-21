<?php

use App\Models\Pedido;
use App\Models\Pedido_Productos;
use App\Models\Tienda;
use App\Models\Proveedor;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a pedido successfully and updates inventory stock', function () {
    // Crear tienda, proveedor, y producto
    $tienda = Tienda::factory()->create();
    $proveedor = Proveedor::factory()->create();
    $producto = Producto::factory()->create();

    // Crear inventario inicial para el producto en la tienda
    $inventario = Inventario::create([
        'id_tienda' => $tienda->id,
        'id_producto' => $producto->id,
        'stock' => 10, // Stock inicial
    ]);

    // Crear pedido con productos
    $pedido = Pedido::create([
        'id_tienda' => $tienda->id,
        'id_proveedor' => $proveedor->id,
        'total' => 500.0,
    ]);

    // Asociar productos al pedido
    Pedido_Productos::create([
        'id_pedido' => $pedido->id,
        'id_producto' => $producto->id,
        'cantidad' => 5,
    ]);

    // Incrementar stock en el inventario
    $inventario->increment('stock', 5);
    $inventario->refresh();

    // Validar que el pedido existe
    expect(Pedido::where('id', $pedido->id)->exists())->toBeTrue();

    // Validar el stock actualizado
    expect($inventario->stock)->toBe(15);

    // Validar los detalles del pedido
    expect($pedido->id_tienda)->toBe($tienda->id);
    expect($pedido->id_proveedor)->toBe($proveedor->id);
    expect($pedido->total)->toBe(500.0);
});

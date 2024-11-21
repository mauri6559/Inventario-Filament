<?php

use App\Models\Venta;
use App\Models\Tienda;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\Venta_Productos;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a venta successfully and reduces stock', function () {
    // Crear una tienda
    $tienda = Tienda::create([
        'nombre' => 'Tienda Prueba',
        'direccion' => 'Calle Falsa 123',
    ]);

    // Crear un cliente
    $cliente = Cliente::create([
        'razon_social' => 'Cliente Ejemplo',
        'email' => 'cliente@ejemplo.com',
        'direccion' => 'Calle Cliente 456',
        'telefono' => '123456789',
    ]);

    // Crear inventario inicial
    $inventario1 = Inventario::create([
        'id_tienda' => $tienda->id,
        'id_producto' => 1, // Producto 1
        'stock' => 100,
    ]);

    $inventario2 = Inventario::create([
        'id_tienda' => $tienda->id,
        'id_producto' => 2, // Producto 2
        'stock' => 50,
    ]);

    // Crear la venta
    $venta = Venta::create([
        'id_tienda' => $tienda->id,
        'id_cliente' => $cliente->id,
        'total' => 400.0,
        'fecha' => now(),
    ]);

    // Asignar productos a la venta
    Venta_Productos::create([
        'id_venta' => $venta->id,
        'id_producto' => 1,
        'cantidad' => 20,
        'precio_unitario' => 10,
    ]);

    Venta_Productos::create([
        'id_venta' => $venta->id,
        'id_producto' => 2,
        'cantidad' => 10,
        'precio_unitario' => 20,
    ]);

    // Reducir el stock en el inventario
    $inventario1->decrement('stock', 20);
    $inventario2->decrement('stock', 10);

    // Verificar que la venta se creó correctamente
    expect(Venta::where('id', $venta->id)->exists())->toBeTrue();
    expect($venta->total)->toBe(400.0);
    expect($venta->id_tienda)->toBe($tienda->id);
    expect($venta->id_cliente)->toBe($cliente->id);

    // Verificar que el inventario se actualizó
    $inventario1->refresh();
    $inventario2->refresh();

    expect($inventario1->stock)->toBe(80); // 100 - 20
    expect($inventario2->stock)->toBe(40); // 50 - 10
});

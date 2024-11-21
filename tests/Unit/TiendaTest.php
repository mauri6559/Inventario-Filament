<?php

use App\Models\Tienda;
use App\Models\Inventario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a tienda successfully', function () {
    $tienda = Tienda::create([
        'nombre' => 'Tienda Ejemplo',
        'direccion' => 'Calle Falsa 123',
    ]);

    expect(Tienda::where('nombre', 'Tienda Ejemplo')->exists())->toBeTrue();
    expect($tienda->nombre)->toBe('Tienda Ejemplo');
    expect($tienda->direccion)->toBe('Calle Falsa 123');
});

test('it updates a tienda successfully', function () {
    $tienda = Tienda::create([
        'nombre' => 'Tienda Inicial',
        'direccion' => 'Calle Inicial 456',
    ]);

    $tienda->update([
        'nombre' => 'Tienda Actualizada',
        'direccion' => 'Calle Actualizada 789',
    ]);

    $tienda->refresh();
    expect($tienda->nombre)->toBe('Tienda Actualizada');
    expect($tienda->direccion)->toBe('Calle Actualizada 789');
});

test('it deletes a tienda successfully', function () {
    $tienda = Tienda::create([
        'nombre' => 'Tienda para Eliminar',
        'direccion' => 'Calle Para Eliminar 123',
    ]);

    $tienda->delete();

    expect(Tienda::where('nombre', 'Tienda para Eliminar')->exists())->toBeFalse();
});

test('it has many inventarios', function () {
    $tienda = Tienda::create([
        'nombre' => 'Tienda con Inventario',
        'direccion' => 'Calle Inventario 123',
    ]);

    $producto1 = Inventario::create([
        'id_tienda' => $tienda->id,
        'id_producto' => 1, // Suponiendo que el producto con ID 1 existe
        'stock' => 100,
    ]);

    $producto2 = Inventario::create([
        'id_tienda' => $tienda->id,
        'id_producto' => 2, // Suponiendo que el producto con ID 2 existe
        'stock' => 150,
    ]);

    $inventarios = $tienda->inventario;

    expect($inventarios->count())->toBe(2);
    expect($inventarios->first()->id_producto)->toBe(1);
    expect($inventarios->last()->id_producto)->toBe(2);
});

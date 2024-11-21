<?php

use App\Models\Product;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a producto successfully', function () {
    // Crear y autenticar un usuario, si es necesario
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear el producto con los datos necesarios
    $producto = Producto::create([
        'nombre' => 'Producto de Ejemplo',
        'descripcion' => 'Este es un producto de prueba',
        'precio' => 150.0,
        'precio_unit_compra' => 100.0,
        'id_Categoria' => 1,
        'id_marca' => 2,
    ]);

    // Verificar que el producto existe en la base de datos
    expect(Producto::where('nombre', 'Producto de Ejemplo')->exists())->toBeTrue();

    // Verificar que los valores son correctos
    expect($producto->nombre)->toBe('Producto de Ejemplo');
    expect($producto->descripcion)->toBe('Este es un producto de prueba');
    expect($producto->precio)->toBe(150.0);
    expect($producto->precio_unit_compra)->toBe(100.0);
    expect($producto->id_Categoria)->toBe(1);
    expect($producto->id_marca)->toBe(2);
});
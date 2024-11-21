<?php

use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a proveedor successfully', function () {
    $proveedor = Proveedor::create([
        'razon_social' => 'Proveedor Ejemplo S.A.',
        'email' => 'proveedor@ejemplo.com',
        'telefono' => '123456789',
        'direccion' => 'Calle Falsa 123',
    ]);

    expect(Proveedor::where('razon_social', 'Proveedor Ejemplo S.A.')->exists())->toBeTrue();
    expect($proveedor->razon_social)->toBe('Proveedor Ejemplo S.A.');
    expect($proveedor->email)->toBe('proveedor@ejemplo.com');
    expect($proveedor->telefono)->toBe('123456789');
    expect($proveedor->direccion)->toBe('Calle Falsa 123');
});

test('it updates a proveedor successfully', function () {
    $proveedor = Proveedor::create([
        'razon_social' => 'Proveedor Inicial S.A.',
        'email' => 'inicial@proveedor.com',
        'telefono' => '987654321',
        'direccion' => 'Calle Inicial 456',
    ]);

    $proveedor->update([
        'razon_social' => 'Proveedor Actualizado S.A.',
        'email' => 'actualizado@proveedor.com',
        'telefono' => '111111111',
        'direccion' => 'Calle Actualizada 789',
    ]);

    $proveedor->refresh();
    expect($proveedor->razon_social)->toBe('Proveedor Actualizado S.A.');
    expect($proveedor->email)->toBe('actualizado@proveedor.com');
    expect($proveedor->telefono)->toBe('111111111');
    expect($proveedor->direccion)->toBe('Calle Actualizada 789');
});

test('it deletes a proveedor successfully', function () {
    $proveedor = Proveedor::create([
        'razon_social' => 'Proveedor para Eliminar S.A.',
        'email' => 'eliminar@proveedor.com',
        'telefono' => '222222222',
        'direccion' => 'Calle Para Eliminar 123',
    ]);

    $proveedor->delete();

    expect(Proveedor::where('razon_social', 'Proveedor para Eliminar S.A.')->exists())->toBeFalse();
});

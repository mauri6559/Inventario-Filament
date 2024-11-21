<?php

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a cliente successfully', function () {
    $cliente = Cliente::create([
        'razon_social' => 'Cliente Ejemplo S.A.',
        'email' => 'cliente@ejemplo.com',
        'direccion' => 'Calle Falsa 123',
        'telefono' => '123456789',
    ]);

    expect(Cliente::where('razon_social', 'Cliente Ejemplo S.A.')->exists())->toBeTrue();
    expect($cliente->razon_social)->toBe('Cliente Ejemplo S.A.');
    expect($cliente->email)->toBe('cliente@ejemplo.com');
    expect($cliente->direccion)->toBe('Calle Falsa 123');
    expect($cliente->telefono)->toBe('123456789');
});

test('it updates a cliente successfully', function () {
    $cliente = Cliente::create([
        'razon_social' => 'Cliente Original S.A.',
        'email' => 'original@cliente.com',
        'direccion' => 'Calle Original 123',
        'telefono' => '987654321',
    ]);

    $cliente->update([
        'razon_social' => 'Cliente Actualizado S.A.',
        'email' => 'actualizado@cliente.com',
        'direccion' => 'Calle Actualizada 456',
        'telefono' => '123123123',
    ]);

    $cliente->refresh();
    expect($cliente->razon_social)->toBe('Cliente Actualizado S.A.');
    expect($cliente->email)->toBe('actualizado@cliente.com');
    expect($cliente->direccion)->toBe('Calle Actualizada 456');
    expect($cliente->telefono)->toBe('123123123');
});

test('it deletes a cliente successfully', function () {
    $cliente = Cliente::create([
        'razon_social' => 'Cliente para Eliminar S.A.',
        'email' => 'eliminar@cliente.com',
        'direccion' => 'Calle Para Eliminar 123',
        'telefono' => '111111111',
    ]);

    $cliente->delete();

    expect(Cliente::where('razon_social', 'Cliente para Eliminar S.A.')->exists())->toBeFalse();
});

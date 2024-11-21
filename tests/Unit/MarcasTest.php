

<?php

use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a marca successfully', function () {
    $marca = Marca::create([
        'Nombre' => 'Marca de Ejemplo',
        'Descripcion' => 'Esta es una descripción de prueba',
    ]);

    expect(Marca::where('Nombre', 'Marca de Ejemplo')->exists())->toBeTrue();
    expect($marca->Nombre)->toBe('Marca de Ejemplo');
    expect($marca->Descripcion)->toBe('Esta es una descripción de prueba');
});

test('it updates a marca successfully', function () {
    $marca = Marca::create([
        'Nombre' => 'Marca Original',
        'Descripcion' => 'Descripción Original',
    ]);

    $marca->update([
        'Nombre' => 'Marca Actualizada',
        'Descripcion' => 'Descripción Actualizada',
    ]);

    $marca->refresh();
    expect($marca->Nombre)->toBe('Marca Actualizada');
    expect($marca->Descripcion)->toBe('Descripción Actualizada');
});

test('it deletes a marca successfully', function () {
    $marca = Marca::create([
        'Nombre' => 'Marca a Eliminar',
        'Descripcion' => 'Descripción para eliminar',
    ]);

    $marca->delete();

    expect(Marca::where('Nombre', 'Marca a Eliminar')->exists())->toBeFalse();
});

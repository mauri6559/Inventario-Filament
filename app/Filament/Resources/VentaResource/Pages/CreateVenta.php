<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use App\Models\Venta;
use App\Models\Venta_Productos;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    public function store(Request $request)
    {
        // Inspeccionar todos los datos de la solicitud
        Log::info('Datos de la solicitud:', $request->all());

        // Verifica si hay productos en la solicitud
        if (!$request->has('productos') || empty($request->input('productos'))) {
            throw new \Exception("No se han seleccionado productos.");
        }

        // Luego llama a handleRecordCreation
        $this->handleRecordCreation($request->all());
    }

    protected function handleRecordCreation(array $data): Venta
    {
        Log::info('Datos recibidos para la creación de la venta:', $data);

        // Verifica si el índice 'total' está presente
        if (!isset($data['total'])) {
            throw new \Exception("El campo 'total' no está definido en los datos.");
        }

        // Verifica si el índice 'productos' está presente
        if (!isset($data['productos'])) {
            Log::error("No se encontraron productos en los datos.");
            throw new \Exception("El campo 'productos' no está definido en los datos.");
        }

        // Crear la venta
        $venta = Venta::create([
            'id_tienda' => $data['id_tienda'],
            'id_cliente' => $data['id_cliente'],
            'total' => $data['total'],
            'fecha' => now()->toDateString(),
        ]);

        // Guardar cada producto en la tabla pivot
        foreach ($data['productos'] as $producto) {
            // Asegúrate de que $producto tenga las claves necesarias
            if (isset($producto['id_producto'], $producto['cantidad'], $producto['precio_unitario'])) {
                Venta_Productos::create([
                    'id_venta' => $venta->id,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                ]);

                // Actualizar el stock del inventario
                $inventario = \App\Models\Inventario::where('id_producto', $producto['id_producto'])
                    ->where('id_tienda', $data['id_tienda'])
                    ->first();

                if ($inventario) {
                    $inventario->stock -= $producto['cantidad'];
                    $inventario->save();
                }
            } else {
                Log::warning("Producto incompleto:", $producto);
            }
        }

        return $venta;
    }
}

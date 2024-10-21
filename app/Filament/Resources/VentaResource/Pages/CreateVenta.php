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



}

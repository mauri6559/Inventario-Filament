<?php

namespace App\Filament\Resources;

use App\Filament\Exports\VentaExporter;
use App\Filament\Resources\VentaResource\Pages;
use App\Filament\Resources\VentaResource\RelationManagers;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Venta_Productos;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        // Selección de la tienda
        Select::make('id_tienda')
            ->required()
            ->relationship(name: 'tienda', titleAttribute: 'nombre')
            ->label('Tienda')
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                Log::info('ID de Tienda seleccionado:', ['id_tienda' => $state]);
                // Limpia los productos si cambia la tienda
                $set('productos', []); 
            }),
        Select::make('id_cliente')
            ->required()
            ->relationship(name: 'cliente', titleAttribute:'razon_social')
            ->label('Cliente'),
        DatePicker::make('fecha')
            ->default(Carbon::now())
            ->required(),
        TextInput::make('total')
            ->numeric()
            ->label('Total')
            ->reactive()
            ->dehydrateStateUsing(fn ($state) => round($state, 2) ?? 0) // Redondear a 2 decimales
                ->afterStateHydrated(function (callable $set, callable $get) {
                    // Calcular el total al cargar el formulario
                    $ventaProductos = $get('ventaProductos') ?? [];
                    $total = collect($ventaProductos)->sum('subtotal');
                    $set('total', $total);
                }),

        Section::make('Productos')
            ->description('Selecciona los productos de la venta')
            ->collapsible()
            ->schema([

                Forms\Components\Repeater::make('ventaProductos')
                    ->relationship()
                    ->columnSpanFull()
                    ->grid(2)
                    ->reactive() // Reactividad para el repeater
                    ->afterStateUpdated(function ($state, callable $set) {
                            // Recalcular el total después de actualizar el repeater
                            $total = collect($state)->sum('subtotal');
                            $set('total', $total);
                        })
                    ->schema([
                        Select::make('id_producto')
                            ->required()
                            ->label('Producto')
                            ->options(function (callable $get) {
                                // Usar directamente 'id_tienda' desde el formulario
                                $tiendaId = $get('../../id_tienda'); // Propagación forzada
        
                                Log::info('ID de Tienda dentro del Repeater:', ['id_tienda' => $tiendaId]);
        
                                // Si la tienda está seleccionada, cargar productos del inventario
                                if ($tiendaId) {
                                    return \App\Models\Inventario::where('id_tienda', $tiendaId)
                                        ->where('stock', '>', 0)
                                        ->with('producto')
                                        ->get()
                                        ->mapWithKeys(function ($inventario) {
                                            return [$inventario->id_producto => $inventario->producto->nombre];
                                        });
                                }
                                return [];
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $producto = \App\Models\Producto::find($state);
                                $set('precio_unitario', $producto ? $producto->precio : null);
                            }),
                            TextInput::make('cantidad')
                                ->numeric()
                                ->required()
                                ->label('Cantidad')
                                ->reactive()
                                
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    // Calcular el subtotal después de actualizar la cantidad
                                    $set('subtotal', ($state ?? 0) * ($get('precio_unitario') ?? 0));
                                }),
        
                            TextInput::make('precio_unitario')
                                ->readOnly()
                                ->numeric()
                                ->required()
                                ->label('Precio Unitario')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    // Calcular el subtotal después de actualizar el precio unitario
                                    $set('subtotal', ($get('cantidad') ?? 0) * ($state ?? 0));
                                }),
        
                            TextInput::make('subtotal')
                                ->readOnly()
                                ->numeric()
                                ->label('Subtotal')
                                ->reactive(),
                    ])
            ]),
        

        
    ]);



    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('tienda.nombre')
                ->label('Tienda')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('cliente.razon_social')
                ->label('Cliente')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('fecha')
                ->label('Fecha')
                ->sortable()
                ->date('d/m/Y'), // Cambia el formato de la fecha según tus necesidades
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->sortable()
                ->money('BOB', true) // Formato de moneda, cambia 'USD' según corresponda
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(VentaExporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
        ];
    }
}

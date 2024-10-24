<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PedidoExporter;
use App\Filament\Resources\PedidoResource\Pages;
use App\Filament\Resources\PedidoResource\RelationManagers;
use App\Models\Pedido;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_tienda')
                    ->required()
                    ->relationship(name: 'tienda', titleAttribute: 'nombre')
                    ->label('Tienda')
                    ->reactive()
                    ,
                Select::make('id_proveedor')
                    ->required()
                    ->relationship(name: 'proveedor', titleAttribute:'razon_social')
                    ->label('Proveedor'),
                /*DatePicker::make('fecha')
                    ->default(Carbon::now())
                    ->required(),*/
                TextInput::make('total')
                    ->numeric()
                    ->label('Total')
                    ->reactive()
                    ->dehydrateStateUsing(fn ($state) => round($state, 2) ?? 0),
                Section::make('Productos')
                    ->description('Selecciona los productos de la venta')
                    ->collapsible()
                    ->schema([
        
                        Forms\Components\Repeater::make('pedidoProducto')
                            ->relationship()
                            ->columnSpanFull()
                            ->grid(2)
                            ->schema([
                                Select::make('id_producto')
                                    ->required()
                                    ->label('Producto')
                                    ->relationship(name: 'producto', titleAttribute:'nombre')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $producto = \App\Models\Producto::find($state);
                                        $set('precio_unitario_compra', $producto ? $producto->precio_unit_compra : null);
                                    }),
                                    TextInput::make('cantidad')
                                        ->numeric()
                                        ->required()
                                        ->label('Cantidad')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            // Calcular el subtotal después de actualizar la cantidad
                                            $set('subtotal', ($state ?? 0) * ($get('precio_unitario_compra') ?? 0));
                                        }),
                
                                    TextInput::make('precio_unitario_compra')
                                        ->readOnly()
                                        ->numeric()
                                        ->required()
                                        ->label('Precio Unitario de Compra')
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
                ->sortable(),
                Tables\Columns\TextColumn::make('tienda.nombre')
                    ->label('Tienda')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('proveedor.razon_social')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->sortable()
                    ->money('BOB'), // Asegúrate de que 'BOB' es el formato correcto para Bolivianos
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->sortable()
                    ->dateTime('d/m/Y'), // Formato de fecha
                ])
            ->filters([
                SelectFilter::make('tienda.nombre')
                    ->relationship(name: 'tienda', titleAttribute:'nombre')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(PedidoExporter::class)
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
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedido::route('/create'),
            'edit' => Pages\EditPedido::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\TiendaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarioRelationManager extends RelationManager
{
    protected static string $relationship = 'inventario';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('producto.nombre')
            ->recordTitleAttribute('producto.precio')
            ->columns([
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock'),
                Tables\Columns\TextColumn::make('producto.precio')
                    ->label('Precio'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

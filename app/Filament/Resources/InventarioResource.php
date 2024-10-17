<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    
                    Select::make('id_tienda')
                        ->required()
                        ->relationship(name: 'tienda', titleAttribute:'nombre')
                        ->label('Tienda'),
                    Select::make('id_producto')
                        ->required()
                        ->relationship(name: 'producto', titleAttribute:'nombre')
                        ->label('Producto'),
                    Forms\Components\TextInput::make('stock')
                        ->numeric()
                        ->required()
                        ->label('Stock'),
                        
                ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('tienda.nombre')
                    ->searchable()
                    ->label('Tienda'),
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->searchable()
                    ->label('Producto'),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->label('Stock'),
                Tables\Columns\TextColumn::make('producto.precio')
                    ->searchable()
                    ->sortable()
                    ->label('Precio')
            ])
            ->filters([
                SelectFilter::make('tienda.nombre')
                    ->relationship(name: 'tienda', titleAttribute:'nombre')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInventarios::route('/'),
            'create' => Pages\CreateInventario::route('/create'),
            'edit' => Pages\EditInventario::route('/{record}/edit'),
        ];
    }
}

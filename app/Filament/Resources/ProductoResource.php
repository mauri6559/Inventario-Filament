<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->required()
                        ->maxLength(255)
                        ->label('Nombre'),
                    Forms\Components\TextInput::make('descripcion')
                        ->required()
                        ->maxLength(255)
                        ->label('Descripcion'),
                    TextInput::make('precio')
                        ->numeric()
                        ->label('Precio'),
                    TextInput::make('precio_unit_compra')
                        ->numeric()
                        ->label('Precio unitario de compra'),

                    Select::make('id_marca')
                        ->required()
                        ->relationship(name: 'marca', titleAttribute:'Nombre')
                        ->label('Marca'),
                    Select::make('id_Categoria')
                        ->required()
                        ->relationship(name: 'categoria', titleAttribute:'Nombre')
                        ->label('Categoría')
                        
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable()
                    ->sortable()
                    ->label('Descripción'),
                Tables\Columns\TextColumn::make('precio')
                    ->sortable()
                    ->label('Precio'),
                Tables\Columns\TextColumn::make('precio_unit_compra')
                    ->sortable()
                    ->label('Precio de Compra'),
                Tables\Columns\TextColumn::make('categoria.Nombre')
                    ->searchable()
                    ->label('Categoría'),
                Tables\Columns\TextColumn::make('marca.Nombre')
                    ->searchable()
                    ->label('Marca')
            ])
            ->filters([
                //
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiendaResource\Pages;
use App\Filament\Resources\TiendaResource\RelationManagers;
use App\Filament\Resources\TiendaResource\RelationManagers\InventarioRelationManager;
use App\Models\Tienda;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TiendaResource extends Resource
{
    protected static ?string $model = Tienda::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('direccion')
                        ->required()
                        ->maxLength(255),
                    
                    
                        
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                TextColumn::make('direccion')
                    ->searchable()
                    ->sortable()
                    ->label('Dirección'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado el'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Modificado el'),
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
            InventarioRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTiendas::route('/'),
            'create' => Pages\CreateTienda::route('/create'),
            'edit' => Pages\EditTienda::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ClienteExporter;
use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('razon_social')
                        ->required()
                        ->maxLength(255)
                        ->label('Razón Social'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->label('Email'),
                    Forms\Components\TextInput::make('direccion')
                        ->required()
                        ->maxLength(255)
                        ->label('Direccion'),
                    Forms\Components\TextInput::make('telefono')
                        ->required()
                        ->maxLength(255)
                        ->label('Telefono'),
                        
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('razon_social')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable()
                    ->label('Dirección'),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable()
                    ->label('Teléfono'),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(ClienteExporter::class)
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}

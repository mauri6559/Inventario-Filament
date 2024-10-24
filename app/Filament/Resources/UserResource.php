<?php

namespace App\Filament\Resources;

use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction as ActionsExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Administracion';

    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nombre'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->label('Email'),
                    Forms\Components\DateTimePicker::make('fecha_nacimiento')
                        ->required()
                        ->label('Fecha de nacimiento')
                        ->format('d-M-Y') // Asegúrate de que sea solo la fecha
                        ->displayFormat('d-M-Y'),
                    Forms\Components\TextInput::make('ci')
                        ->required()
                        ->maxLength(255)
                        ->label('CI'),
                    Forms\Components\TextInput::make('telefono')
                        ->required()
                        ->maxLength(255)
                        ->label('Telefono'),
                    
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->mutateDehydratedStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                        ->maxLength(255)
                        ->label('Contraseña'),
                    Select::make('roles')
                        ->multiple()
                        ->relationship(name: 'roles', titleAttribute:'name')
                        ->preload()
                        ->label('Rol'),
                    Select::make('permissions')
                        ->multiple()
                        ->relationship(name: 'permissions', titleAttribute:'name')
                        ->preload()
                        ->label('Permisos')
                        
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->sortable()
                    ->label('Fecha de nacimiento')
                    ->dateTime('d-M-Y'),
                Tables\Columns\TextColumn::make('ci')
                    ->searchable()
                    ->label('CI'),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable()
                    ->label('Telefono'),
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
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->headerActions([
                ExportAction::make()->exporter(UserExporter::class)
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
